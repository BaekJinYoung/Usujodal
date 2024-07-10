<!DOCTYPE html>
<html>
<head>
    <title>Contact Us</title>
    <script>
        function validateForm() {
            var checkbox = document.getElementById('agreeCheckbox');
            if (!checkbox.checked) {
                alert('개인정보 수집 및 이용에 대한 동의(필수)');
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
<form id="contactForm">
    @csrf
    <label for="name">성함:</label>
    <input type="text" id="name" name="name" required><br><br>
    <label for="contact">연락처:</label>
    <input type="text" id="contact" name="contact" required><br><br>
    <label for="company">회사명:</label>
    <input type="text" id="company" name="company"><br><br>
    <label for="email">이메일:</label>
    <input type="email" id="email" name="email"><br><br>
    <label for="message">문의내용:</label>
    <textarea id="message" name="message" required></textarea><br><br>
    <label>
        <input type="checkbox" id="agreeCheckbox"> 개인정보 수집 및 이용에 대한 동의(필수)
    </label>
    <button type="submit">문의하기</button>
</form>

<script>
    document.getElementById('contactForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        if (!validateForm()) {
            return;
        }

        const formData = new FormData(this);
        const response = await fetch('/api/inquiry', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
            },
            body: formData
        });

        const data = await response.json();
        if (response.ok) {
            alert('Your message has been sent successfully!');
        } else {
            alert('There was an error sending your message.');
        }
    });
</script>
</body>
</html>
