function previewSelectedImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const fields = ['salon_name', 'salon_phonenumber', 'salon_pinpoint'];

    form.addEventListener('submit', function (e) {
        let hasError = false;

        document.querySelectorAll('.error').forEach(el => el.remove());
        fields.forEach(field => {
            document.getElementById(field).classList.remove('is-invalid');
        });

        const name = document.getElementById('salon_name').value.trim();
        const phone = document.getElementById('salon_phonenumber').value.trim();
        const pinpoint = document.getElementById('salon_pinpoint').value.trim();

        if (name === '') {
            showError('salon_name', 'Nama salon tidak boleh kosong.');
            hasError = true;
        }

        if (!/^[0][0-9]+$/.test(phone)) {
            showError('salon_phonenumber', 'Nomor telepon harus diawali dengan 0 dan hanya berisi angka.');
            hasError = true;
        }

        try {
            new URL(pinpoint);
        } catch {
            showError('salon_pinpoint', 'Titik lokasi harus berupa tautan (link) yang valid.');
            hasError = true;
        }

        if (hasError) {
            e.preventDefault();
        }
    });

    function showError(fieldId, message) {
        const input = document.getElementById(fieldId);
        input.classList.add('is-invalid');

        const errorDiv = document.createElement('div');
        errorDiv.className = 'error';
        errorDiv.style.color = 'red';
        errorDiv.style.fontSize = '13px';
        errorDiv.textContent = message;

        input.parentNode.insertBefore(errorDiv, input.nextSibling);
    }
});
