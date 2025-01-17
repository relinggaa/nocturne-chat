<!DOCTYPE html>
<html>
<head>
    <title>Enter Username</title>
</head>
<body>
    <form action="{{ route('set.username') }}" method="POST">
        @csrf
        <label for="username">Enter your username:</label>
        <input type="text" name="username" id="username" required>
        <button type="submit">Join Chat</button>
    </form>
</body>
</html>
