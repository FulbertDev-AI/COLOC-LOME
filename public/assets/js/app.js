(() => {
    const choices = document.querySelectorAll('.choice');
    choices.forEach((choice) => {
        choice.addEventListener('click', () => {
            const input = choice.querySelector('input');
            if (!input) return;
            if (input.type === 'radio') {
                choice.parentElement.querySelectorAll('.choice').forEach((el) => el.classList.remove('is-on'));
                choice.classList.add('is-on');
            } else {
                choice.classList.toggle('is-on', input.checked);
            }
        });
    });

    document.querySelectorAll('[data-range-output]').forEach((range) => {
        const out = document.querySelector('[data-range-value]');
        const paint = () => {
            if (out) {
                out.textContent = Number(range.value).toLocaleString('fr-FR') + ' FCFA';
            }
        };
        range.addEventListener('input', paint);
        paint();
    });

    const rent = document.querySelector('[data-rent]');
    if (rent) {
        const update = () => {
            const base = Number(rent.value || 0);
            const caution = base * 2;
            const fmt = (n) => n.toLocaleString('fr-FR') + ' FCFA';
            const baseEl = document.querySelector('[data-base]');
            const cautionEl = document.querySelector('[data-caution]');
            const totalEl = document.querySelector('[data-total]');
            if (baseEl) baseEl.textContent = fmt(base);
            if (cautionEl) cautionEl.textContent = fmt(caution);
            if (totalEl) totalEl.textContent = fmt(base + caution);
        };
        rent.addEventListener('input', update);
        update();
    }

    const composer = document.querySelector('.composer textarea');
    if (composer) {
        composer.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                composer.form?.submit();
            }
        });
    }

    const toggle = document.querySelector('[data-menu-toggle]');
    const nav = document.getElementById('site-nav');
    const backdrop = document.querySelector('[data-menu-close]');
    const setMenu = (open) => {
        document.body.classList.toggle('menu-open', open);
        toggle?.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle?.setAttribute('aria-label', open ? 'Fermer le menu' : 'Ouvrir le menu');
        if (backdrop) {
            backdrop.hidden = !open;
        }
    };
    toggle?.addEventListener('click', () => setMenu(!document.body.classList.contains('menu-open')));
    backdrop?.addEventListener('click', () => setMenu(false));
    nav?.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setMenu(false)));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setMenu(false);
        }
    });
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            setMenu(false);
        }
    });
})();
