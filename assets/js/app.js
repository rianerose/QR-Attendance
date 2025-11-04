document.addEventListener('DOMContentLoaded', () => {
    const timeTags = document.querySelectorAll('[data-attendance-time]');
    timeTags.forEach((tag) => {
        const raw = tag.getAttribute('data-attendance-time');
        if (!raw) return;
        const formatted = dayjs(raw).format('MMM D, YYYY h:mm A');
        tag.textContent = formatted;
    });

    document.querySelectorAll('[data-copy]').forEach((button) => {
        button.addEventListener('click', async () => {
            const value = button.getAttribute('data-copy');
            if (!value) return;
            try {
                await navigator.clipboard.writeText(value);
                button.textContent = 'Copied!';
                setTimeout(() => {
                    button.textContent = 'Copy Token';
                }, 1500);
            } catch (error) {
                console.error('Copy failed', error);
            }
        });
    });
});

