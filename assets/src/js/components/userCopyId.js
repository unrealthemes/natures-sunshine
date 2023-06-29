export default function () {
    const copyUserID = document.querySelector('.js-copy-id');

    const copyTextMessage = () => {
        const messageText = copyUserID.dataset.text;
        const message = document.createElement('span');
        message.className = 'user-info__id-copy-message';
        message.textContent = messageText;
        copyUserID.append(message);

        setTimeout(() => {
            message.remove();
        }, 1500);
    }

    const fallbackCopyTextToClipboard = text => {
        const textArea = document.createElement("textarea");
        textArea.value = text;

        // Avoid scrolling to bottom
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";

        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            const successful = document.execCommand('copy');
            const msg = successful ? 'successful' : 'unsuccessful';

            copyTextMessage();

            // console.log('Fallback: Copying text command was ' + msg);
        } catch (err) {
            console.error('Fallback: Oops, unable to copy', err);
        }

        document.body.removeChild(textArea);
    }

    const copyTextToClipboard = text => {
        if (!navigator.clipboard) {
            fallbackCopyTextToClipboard(text);
            return;
        }
        navigator.clipboard.writeText(text).then(() => {
            copyTextMessage();
            console.log('Async: Copying to clipboard was successful!');
        }, err => {
            console.error('Async: Could not copy text: ', err);
        });
    }

    copyUserID && copyUserID.addEventListener('click', e => {
        e.preventDefault();
        const userID = e.currentTarget.previousElementSibling.dataset.userId;
        copyTextToClipboard(userID);
    });
}