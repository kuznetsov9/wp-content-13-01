
/// ---------> Tabs (Add Post)
document.addEventListener("DOMContentLoaded", function() {
    const tabButtons = document.querySelectorAll('.fred-tab-button');
    const tabPanels = document.querySelectorAll('.fred-tab-panel');

    tabButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault();

            const targetTab = button.getAttribute('data-tab');

            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanels.forEach(panel => panel.classList.remove('active'));

            button.classList.add('active');
            document.querySelector(`.fred-tab-panel[data-tab="${targetTab}"]`).classList.add('active');
        });
    });
});

/// ---------> Select (Add Themes)
document.addEventListener('DOMContentLoaded', function() {
    const customSelects = document.querySelectorAll('.esn-custom-select');

    customSelects.forEach(customSelect => {
        const selected = customSelect.querySelector('.esn-custom-select-selected');
        const optionsList = customSelect.querySelector('.esn-custom-select-options');
        const options = customSelect.querySelectorAll('.esn-custom-select-option');
        const hiddenInput = customSelect.querySelector('input[type="hidden"]');

        selected.addEventListener('click', function(e) {
            e.stopPropagation();

            document.querySelectorAll('.esn-custom-select-options').forEach(list => {
                if (list !== optionsList) {
                    list.style.display = 'none';
                }
            });

            optionsList.style.display = optionsList.style.display === 'block' ? 'none' : 'block';
        });

        options.forEach(option => {
            option.addEventListener('click', function(e) {
                e.stopPropagation();
                options.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                selected.innerHTML = this.innerHTML;
                hiddenInput.value = this.dataset.value;
                selected.dataset.value = this.dataset.value;
                optionsList.style.display = 'none';
            });
        });


         document.addEventListener('click', function(event) {
            if (!customSelect.contains(event.target)) {
              optionsList.style.display = 'none';
            }
          });

    });	
});