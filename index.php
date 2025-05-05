<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 50px;
        }

        .login-container {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }

        button {
            margin-top: 20px;
            padding: 10px;
            width: 100%;
            background-color: #007BFF;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        pre {
            margin-top: 20px;
            background: #eee;
            padding: 10px;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form id="loginForm">
            <label for="email">Email:</label>
            <input type="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" required>

            <button type="submit">Login</button>
        </form>

        <pre id="response"></pre>
    </div>

    <script>

    document.getElementById("loginForm").addEventListener("submit", function(e) {
        e.preventDefault();

        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        fetch("http://localhost/kiemtr2_nhom09/api/login.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ email, password })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById("response").textContent = JSON.stringify(data, null, 2);

            // Nếu login thành công, chuyển hướng
            if (data.message === "Successful login." && data.jwt) {
                // Lưu JWT nếu cần: localStorage.setItem("jwt", data.jwt);
                window.location.href = "http://localhost/kiemtr2_nhom09/product";
            }
        })
        .catch(error => {
            document.getElementById("response").textContent = "Error: " + error;
        });
    });
</script>   

</body>
</html>
