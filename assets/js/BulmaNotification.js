export const initDeleteButton = () => {
    const buttons = [...document.querySelectorAll('.notification > button.delete')]

    buttons.forEach(button => {
        button.addEventListener('click', e => e.currentTarget.parentNode.remove())
    })
}