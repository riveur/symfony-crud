export const initToggleButtons = () => {
    const buttons = [...document.querySelectorAll('button[data-toggle="toggle"]')]
        .filter(button => ![undefined, null].includes(button.dataset.target))

    buttons.forEach(button => {
        const target = document.querySelector(button.dataset.target)

        if (target) {
            button.addEventListener('click', () => {
                target.style.display = target.style.display === 'block' ? 'none' : 'block';
            })
        }
    })
}