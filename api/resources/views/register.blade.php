<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="{{route('signup')}}" method="post">
        @csrf
        <input type="text" name="nome" placeholder="name" maxlength="255" required><br>
        <input type="email" name="email" placeholder="email" maxlength="255" required><br>
        <select name="equipe" id="equipe">
            <option value="" selected disabled>Selecione uma opção</option>
            <option value="Gerente de Projeto">Gerente de Projeto</option>
            <option value="Design">Design</option>
            <option value="Desenvolvimento">Desenvolvimento</option>
        </select> <br>
        <input type="text" name="username" placeholder="username" maxlength="255" required><br>
        <input type="password" name="senha" placeholder="senha" maxlength="255" required><br>

        <button type="submit">Enviar</button>
    </form>
</body>
</html>