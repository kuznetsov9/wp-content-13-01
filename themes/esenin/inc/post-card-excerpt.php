<?php

function es_excerpt( $args = '' ){
	global $post;

	if( is_string( $args ) ){
		parse_str( $args, $args );
	}

	$rg = (object) array_merge( [
		'maxchar'           => 500,
		'text'              => '',
		'autop'             => true,
		'more_text'         => ' ...',
		'ignore_more'       => true,
		'save_tags' => '<strong><b><a><em><i><var><code><span><mark><ul><ol><li><div><iframe><br><p><cite>',
		'sanitize_callback' => static function( string $text, object $rg ){
			return strip_tags( $text, $rg->save_tags );
		},
	], $args );

	$rg = apply_filters( 'es_excerpt_args', $rg );

	if( ! $rg->text ){
		$rg->text = $post->post_excerpt ?: $post->post_content;
	}

	$text = $rg->text;
	// strip content shortcodes: [foo]some data[/foo]. Consider markdown
	$text = preg_replace( '~\[([a-z0-9_-]+)[^\]]*\](?!\().*?\[/\1\]~is', '', $text );
	// strip others shortcodes: [singlepic id=3]. Consider markdown
	$text = preg_replace( '~\[/?[^\]]*\](?!\()~', '', $text );
	// strip direct URLs
	$text = preg_replace( '~(?<=\s)https?://.+\s~', '', $text );
$text = preg_replace( '~<figcaption[^>]*>.*?</figcaption>~is', '', $text );
	$text = trim( $text );

	// <!--more-->
	if( ! $rg->ignore_more && strpos( $text, '<!--more-->' ) ){

		preg_match( '/(.*)<!--more-->/s', $text, $mm );

		$text = trim( $mm[1] );

		$text_append = sprintf( ' <a href="%s#more-%d">%s</a>', get_permalink( $post ), $post->ID, $rg->more_text );
	}
	// text, excerpt, content
	else {

		$text = call_user_func( $rg->sanitize_callback, $text, $rg );
		$has_tags = false !== strpos( $text, '<' );

		// collect html tags
		if( $has_tags ){
			$tags_collection = [];
			$nn = 0;

			$text = preg_replace_callback( '/<[^>]+>/', static function( $match ) use ( & $tags_collection, & $nn ){
				$nn++;
				$holder = "~$nn";
				$tags_collection[ $holder ] = $match[0];

				return $holder;
			}, $text );
		}

		// cut text
		$cuted_text = mb_substr( $text, 0, $rg->maxchar );
		if( $text !== $cuted_text ){

			// del last word, it not complate in 99%
			$text = preg_replace( '/(.*)\s\S*$/s', '\\1...', trim( $cuted_text ) );
		}

		// bring html tags back
		if( $has_tags ){
			$text = strtr( $text, $tags_collection );
			$text = force_balance_tags( $text );
		}
	}

// add <p> tags. 
if( $rg->autop ){
    // 1. ПРЕОБРАЗОВАНИЕ: превращаем существующие закрывающие </p> в переносы строк
    // Это чтобы текст не склеился, когда мы будем пересобирать параграфы
    $text = preg_replace('/<\/p>/i', "\n\n", $text);

    // 2. СНОСИМ ОСТАТКИ: удаляем все открывающие <p> и другие остатки параграфов
    $text = preg_replace('/<p[^>]*>/i', '', $text);

    // 3. ГИГИЕНА: вычищаем невидимый мусор и переносы в начале и конце
    $text = preg_replace('/^[\s\p{Z}\p{Cc}\x{00a0}]+/u', '', $text);
    $text = preg_replace('/[\s\p{Z}\p{Cc}\x{00a0}]+$/u', '', $text);

    if ( empty($text) ) {
        return '';
    }

    // 4. СБОРКА: создаем чистую структуру на основе двойных переносов
    // Теперь у нас точно не будет вложенных <p><p>
    $text = "<p>" . preg_replace("/(\r\n|\r|\n){2,}/", "</p><p>", $text) . "</p>";
    
    // 5. ФИНАЛЬНЫЙ КИЛЛЕР: удаляем параграфы, если они пустые или в них только пробелы
    $text = preg_replace_callback('/<p[^>]*>(.*?)<\/p>/is', function($match) {
        $inner = trim(strip_tags($match[1], '<img><iframe><figure>'));
        $inner = str_replace(['&nbsp;', "\xc2\xa0"], '', $inner);
        
        // Если внутри пусто — сносим нафиг
        return (empty($inner)) ? '' : $match[0];
    }, $text);

    // Подчищаем одиночные переносы
    $text = str_replace("\n", " ", $text);
}

	$text = apply_filters( 'es_excerpt', $text, $rg );

	if( isset( $text_append ) ){
		$text .= $text_append;
	}

	return $text;
}