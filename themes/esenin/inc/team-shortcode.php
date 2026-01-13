<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_shortcode('football_team_page', function($atts) {
    $atts = shortcode_atts(['id' => '168719'], $atts);
    $team_id = $atts['id'];

    if (isset($_GET['force_update']) && function_exists('bz_fetch_team_data')) {
        bz_fetch_team_data($team_id);
    }

    $raw = get_option('bz_team_raw_' . $team_id);
    $data = bz_get_prepared_team_data($raw, $team_id);

    if (!$data) return '<p>Данные загружаются...</p>';

    $fallback = "this.onerror=null;this.src='https://www.fotmob.com/img/player-fallback-dark.png';";

    ob_start(); ?>
    <div class="bz-wrap">
        

            <div class="bz-meta">
                <?php if($data['current_coach']): ?>
                <div class="bz-pill">
                    <img src="https://images.fotmob.com/image_resources/playerimages/<?php echo $data['current_coach']['id']; ?>.png" onerror="<?php echo $fallback; ?>">
                    <span><?php echo esc_html($data['current_coach']['name']); ?></span>
                </div>
                <?php endif; ?>
                <div class="bz-stadium-txt">Стадион:
                    <?php echo $data['stadium']['name']; ?> (<?php echo $data['stadium']['cap']; ?>)
                </div>
            </div>
		
        <div class="bz-box">
            <div class="bz-cap">Форма</div>
            <div class="bz-form-list">
                <?php if(!empty($data['form'])): foreach ($data['form'] as $f): ?>
                    <div class="bz-f-item">
                        <span class="bz-badge is-<?php echo strtolower($f['res']); ?>"><?php echo $f['score']; ?></span>
                        <?php if($f['opp_id']): ?>
                            <img src="https://images.fotmob.com/image_resources/logo/teamlogo/<?php echo $f['opp_id']; ?>_xsmall.png">
                        <?php endif; ?>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>		


        <div class="bz-box">
            <div class="bz-cap">Последний состав и оценка за сезон</div>
            <div class="bz-pitch-container">
                <div class="bz-pitch-svg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="256" height="139" viewBox="0 0 316 174">
                        <g fill="#666">
                            <path d="M11.813 150.407h90.813a76.778 76.778 0 0 0 110.748 0h90.813A11.839 11.839 0 0 0 316 138.61V0h-5.906v138.61a5.92 5.92 0 0 1-5.907 5.9H11.813a5.92 5.92 0 0 1-5.907-5.9V0H0v138.61a11.84 11.84 0 0 0 11.813 11.797zm193 0a70.761 70.761 0 0 1-93.619 0z"/>
                            <path d="M84.168 0v50.136a5.92 5.92 0 0 0 5.907 5.9H192.85a5.92 5.92 0 0 0 5.907-5.9V0"/>
                        </g>
                    </svg>
                </div>
                <?php foreach ($data['lineup'] as $p): 
                    $top = (1 - $p['x']) * 100; $left = $p['y'] * 100;
                ?>
                    <div class="bz-p-node" style="top:<?php echo $top; ?>%; left:<?php echo $left; ?>%;">
                        <div class="bz-p-avi">
                            <img src="https://images.fotmob.com/image_resources/playerimages/<?php echo $p['id']; ?>.png" onerror="<?php echo $fallback; ?>">
                            <span class="bz-rate" style="background:<?php echo (float)$p['rating'] >= 7 ? '#109848' : '#d97706'; ?>"><?php echo $p['rating']; ?></span>
                            <?php if($p['goals']>0 || $p['assists']>0): ?>
                                <div class="bz-actions">
                                    <?php if($p['goals']>0): ?><span>⚽<?php echo $p['goals']; ?></span><?php endif; ?>
                                    <?php if($p['assists']>0): ?><span>👟<?php echo $p['assists']; ?></span><?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="bz-p-name"><?php echo esc_html($p['name']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="bz-split">
            <div class="bz-box">
                <div class="bz-cap">ТОП-3 Бомбардиры</div>
                <?php if(!empty($data['scorers'])): foreach ($data['scorers'] as $s): ?>
                    <div class="bz-sc-row">
                        <img src="https://images.fotmob.com/image_resources/playerimages/<?php echo $s['id']; ?>.png" onerror="<?php echo $fallback; ?>">
                        <span><?php echo $s['name']; ?></span>
                        <strong><?php echo $s['goals']; ?></strong>
                    </div>
                <?php endforeach; endif; ?>
            </div>

            <div class="bz-box">
                <div class="bz-cap">Календарь</div>
                <?php if(!empty($data['upcoming'])): foreach ($data['upcoming'] as $m): ?>
                    <div class="bz-match-row">
                        <div class="bz-m-left">
                            <span class="bz-date"><?php echo $m['date']; ?></span>
                            <small><?php echo $m['tour']; ?></small>
                        </div>
                        <div class="bz-m-right">
                            <img src="https://images.fotmob.com/image_resources/logo/teamlogo/<?php echo $m['opp_id']; ?>_xsmall.png">
                            <strong><?php echo $m['opp']; ?></strong>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
		
        <div class="bz-box" id="bzCoachContainer" style="position:relative; z-index:1;">
            <div class="bz-cap">История тренеров</div>
            
            <div class="bz-slider-wrap">
                <button class="bz-nav-btn bz-prev" onclick="document.getElementById('bzCoachScroll').scrollBy({left: -200, behavior: 'smooth'})">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>

                <div class="bz-coach-scroll" id="bzCoachScroll">
                    <div id="bz-float-tip" class="bz-c-tooltip">
                        <div class="bz-tt-head"><span id="tt-league"></span><span id="tt-season"></span></div>
                        <div class="bz-tt-stats">
                            <div class="st-box st-w"><span class="badge">В</span> <span id="tt-w"></span></div>
                            <div class="st-box st-d"><span class="badge">Н</span> <span id="tt-d"></span></div>
                            <div class="st-box st-l"><span class="badge">П</span> <span id="tt-l"></span></div>
                        </div>
                    </div>

                    <?php if(!empty($data['coach_history'])): foreach ($data['coach_history'] as $c): ?>
                        <div class="bz-c-item" 
                             data-w="<?php echo $c['w']; ?>" 
                             data-d="<?php echo $c['d']; ?>" 
                             data-l="<?php echo $c['l']; ?>"
							 data-league="<?php echo $c['league']; ?>"
                             data-season="<?php echo $c['season']; ?>"
                        >
                            <div class="bz-c-top">
                                <span class="c-win" style="background:<?php echo $c['win_p'] > 50 ? '#109848' : '#d97706'; ?>"><?php echo $c['win_p']; ?>%</span>
                                <span class="c-ppg"><?php echo $c['ppg']; ?> оч.</span>
                            </div>
                            <div class="bz-c-bar-area">
                                <div class="bz-c-bar" style="height: <?php echo max(5, (float)$c['ppg'] * 30); ?>px"></div>
                            </div>
                            <div class="bz-c-bot">
                                <img src="https://images.fotmob.com/image_resources/playerimages/<?php echo $c['id']; ?>.png" onerror="<?php echo $fallback; ?>">
                                <span class="c-name"><?php echo $c['name']; ?></span>
                                <span class="c-season"><?php echo $c['season']; ?></span>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>

                <button class="bz-nav-btn bz-next" onclick="document.getElementById('bzCoachScroll').scrollBy({left: 200, behavior: 'smooth'})">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
        </div>		

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('bzCoachScroll');
        
        // 1. АВТО-СКРОЛЛ В КОНЕЦ (К текущему тренеру)
        if(slider) {
            slider.scrollLeft = slider.scrollWidth;
        }

        // 2. ХОВЕР ЛОГИКА ТУЛТИПА
        const items = document.querySelectorAll('.bz-c-item');
        const tooltip = document.getElementById('bz-float-tip');
        const container = document.getElementById('bzCoachContainer');

        if(items && tooltip && container) {
            items.forEach(item => {
                item.addEventListener('mouseenter', function() {
					document.getElementById('tt-league').textContent = this.dataset.league;
                    document.getElementById('tt-season').textContent = this.dataset.season;
                    document.getElementById('tt-w').textContent = this.dataset.w;
                    document.getElementById('tt-d').textContent = this.dataset.d;
                    document.getElementById('tt-l').textContent = this.dataset.l;

                    // Центрируем тултип над карточкой
                    const rect = this.getBoundingClientRect();
                    const containerRect = container.getBoundingClientRect();
                    let leftPos = rect.left - containerRect.left + (rect.width / 2);
                    
                    tooltip.style.left = leftPos + 'px';
                    tooltip.style.opacity = '1';
                    tooltip.style.visibility = 'visible';
                });

                item.addEventListener('mouseleave', function() {
                    tooltip.style.opacity = '0';
                    tooltip.style.visibility = 'hidden';
                });
            });
        }
    });
    </script>

    <style>
        /* CSS VARIABLES ESENIN */
		
        .bz-wrap { background: var(--es-layout-background); color: var(--es-color-primary); max-width: 900px; margin: 0 auto; }
        .bz-box { background: var(--es-header-background); padding: 20px; margin-bottom: 20px; border-radius: var(--es-input-border-radius); border: solid 1px var(--es-color-border); }
        .bz-cap { color: var(--es-color-secondary); font-size: 11px; text-transform: uppercase; font-weight: 700; margin-bottom: 20px; letter-spacing: 0.5px; }
        h1 { margin: 0; font-size: 28px; line-height: 1.2; color: var(--es-color-primary); }

        /* HEADER */
        .bz-head { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
        .bz-meta { display: flex; align-items: center; gap: 15px; margin-bottom: 20px }
        .bz-pill { display: flex; align-items: center; gap: 8px; background: var(--es-header-background); padding: 4px 12px; border-radius: 20px; color: var(--es-color-primary); font-size: 13px; border: 1px solid var(--es-color-border); }
        .bz-pill img { width: 24px; height: 24px; border-radius: 50%; object-fit: cover; }
        .bz-stadium-txt { color: var(--es-color-secondary); font-size: 13px; }

        /* PITCH */
        .bz-pitch-container { position: relative; width: 100%; height: 460px; background: rgba(255,255,255,0.02); border-radius: 8px; margin-top: 10px; }
        .bz-pitch-svg { position: absolute; bottom: -20px; left: 48%; transform: translateX(-50%) rotate(180deg); width: 260px; height: 140px; opacity: 0.2; pointer-events: none; }
        .bz-pitch-svg svg { width: 100%; height: 100%; }
        .bz-p-node { position: absolute; transform: translate(-50%, -50%); text-align: center; width: 70px; z-index: 5; }
        .bz-p-avi { position: relative; width: 44px; height: 44px; margin: 0 auto; }
        .bz-p-avi img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; }
        .bz-rate { position: absolute; top: -12px; right: -12px; color: #fff; font-size: 10px; padding: 1px 5px 0px 5px; border-radius: 6px; font-weight: 600;}
		.bz-actions img.wp-smiley, bz-actions. img.emoji {margin: 0 0 1px 0 !important;}
        .bz-actions { position: absolute; bottom: -2px; left: 50%; transform: translateX(-50%); display: flex ; gap: 2px; width: 80px; justify-content: space-between; }
		.bz-actions span { background: #111; color: #fff; font-size: 11px; font-weight: 500; padding: 0px 6px 0px 4px; border-radius: 50px; display: flex; flex-direction: row; width: max-content; align-items: center; gap: 1px;}
        .bz-p-name { margin-top:4px;font-size: 12px; color: var(--es-color-primary); font-weight: 600; text-shadow: none; }

        /* SLIDER WRAPPER */
        .bz-slider-wrap { position: relative; width: 100%; padding: 0 10px; box-sizing: border-box; }

        /* BUTTONS (ON THE SIDES) */
        .bz-nav-btn {
            position: absolute; top: 50%; transform: translateY(-50%); z-index: 9;
            width: 36px; height: 36px; border-radius: 50%;
            background: var(--es-layout-background);
            border: 1px solid var(--es-color-border);
            color: var(--es-color-primary, #fff);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 0;box-shadow:none;
        }
        .bz-nav-btn.bz-prev { left: -36px; }
        .bz-nav-btn.bz-next { right: -36px; }
		.bz-nav-btn svg
		{color: var(--es-color-contrast-300)}
		.bz-nav-btn:hover svg
		{color: var(--es-color-contrast-600)}
		.bz-nav-btn.bz-prev svg { margin-left: -2px;}
		.bz-nav-btn.bz-next svg { margin-right: -2px;}
        
        .bz-coach-scroll { 
            display: flex; overflow-x: auto; 
            padding-top: 32px; /* Space for tooltip */
            padding-bottom: 10px;
            margin-top: -20px; 
            scroll-behavior: smooth; 
            scrollbar-width: none; 
            /* GAP REMOVED, USING PADDING ON ITEMS */
            gap: 0; 
        }
        .bz-coach-scroll::-webkit-scrollbar { display: none; }
        
        .bz-c-item { 
            flex: 0 0 95px; /* Ширина карточки */
            display: flex; flex-direction: column; align-items: center; justify-content: flex-end; 
            height: 180px; position: relative; 
            padding: 0 5px; /* Вместо gap */
            box-sizing: border-box;
            cursor: pointer;
        }
        .bz-c-item:hover .bz-c-bar { opacity: 0.8; }

        /* FIXED TOOLTIP */
        .bz-c-tooltip { 
            position: absolute; top: 30px; left: 0; transform: translateX(-50%); 
            background: #2b2b2b; color: #fff;
            border: 1px solid #444; padding: 10px; border-radius: 8px; 
            font-size: 11px; white-space: nowrap; z-index: 99;
            opacity: 0; visibility: hidden; pointer-events: none;
            transition: opacity 0.1s;
            text-align: center;
        }
        .bz-tt-head { display:inline-grid;border-bottom: 1px solid #444; padding-bottom: 6px; margin-bottom: 6px; font-weight: 700; color: #ccc; }
        .bz-tt-stats { display: flex; justify-content: center; gap: 8px; }
        .st-box { font-weight: 700; display: flex; align-items: center; gap: 4px; }
        .badge { display: inline-flex; align-items: center; justify-content: center; width: 16px; height: 16px; border-radius: 4px; font-size: 10px; font-weight: 700; color: #fff; }
        .st-w .badge { background: #008f4c; }
        .st-d .badge { background: #5e646e; }
        .st-l .badge { background: #e12c2c; }

        .bz-c-top { display: flex; flex-direction: column; align-items: center; margin-bottom: 5px; line-height: 1; width: 100%; }
        .c-win { font-size: 10px; color: #fff; font-weight: 500; padding: 3px 4px 2px 4px; border-radius: 17px; margin-bottom: 5px; }
        .c-ppg { font-size: 10px; color: var(--es-color-secondary); font-weight: 400; }
        .bz-c-bar-area { width: 100%; display: flex; justify-content: center; align-items: flex-end; height: 100px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .bz-c-bar { width: 32px; background: var(--es-color-contrast-200); border-radius: 4px 4px 0 0; min-height: 4px; position: relative; }
        .bz-c-bar::after { content:''; display:block; background:#109848; width:100%; position:absolute; bottom:0; height: 30%; border-radius: 4px 4px 0 0; }
        .bz-c-bot { margin-top: 10px; text-align: center; }
        .bz-c-bot img { width: 36px; height: 36px; border-radius: 50%; border: 1px solid var(--es-color-border); display: block; margin: 0 auto 5px; object-fit: cover; }
        .c-name { display: block; font-size: 9px; line-height: 1.1; height: 22px; overflow: hidden; color: var(--es-color-primary); }
        .c-season { display: block; font-size: 9px; color: var(--es-color-secondary); }

        /* SPLIT */
        .bz-split { display: grid; grid-template-columns: 1fr 1fr; gap: 0 20px; }
        
        .bz-sc-row { display: flex; align-items: center; gap: 12px; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .bz-sc-row:last-child { border: none; }
        .bz-sc-row img { width: 30px; height: 30px; border-radius: 50%; object-fit: cover; }
        .bz-sc-row span { font-size: 13px; color: var(--es-color-primary, #fff); flex: 1; font-weight:500}
        .bz-sc-row strong { color: #109848; font-size: 14px; }

        .bz-match-row { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .bz-m-left { display: flex; flex-direction: column; }
        .bz-date { font-size: 13px; color: var(--es-color-primary, #fff); font-weight: 600; }
        .bz-m-left small { font-size: 11px; color: var(--es-color-secondary); }
        .bz-m-right { display: flex; align-items: center; gap: 10px; }
        .bz-m-right strong { font-size: 13px; color: var(--es-color-primary); }
        .bz-m-right img { width: 24px; }

        /* FORM */
        .bz-form-list { display: flex; justify-content: space-between; gap: 5px; }
        .bz-f-item { text-align: center; flex: 1; display: flex ; flex-direction: column; align-items: center; }
        .bz-badge { display: block; font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 6px; margin-bottom: 8px; color: #fff; width: fit-content; }
        .is-w { background: #109848; } .is-d { background: #5e646e; } .is-l { background: #e12c2c; }
        .bz-f-item img { width: 24px; opacity: 0.8; }

        @media (max-width: 600px) { .bz-split { grid-template-columns: 1fr; } }
    </style>
    <?php return ob_get_clean();
});