<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="{{route('tarefa.store')}}" method="post">
        @csrf
        <input type="text" placeholder="titulo" name="titulo" maxlength="255" required> <br>
        <input type="text" placeholder="descricao" name="descricao" maxlength="255" required> <br>
        <input type="date" placeholder="prazo" name="prazo" maxlength="255" required> <br>
        <input type="text" placeholder="equipe" name="equipe" maxlength="255" > <br>
        <input type="text" placeholder="prioridade" name="prioridade" maxlength="255" > <br>
        <input type="text" placeholder="status" name="status" maxlength="255" > <br>
        <input type="text" placeholder="projeto" name="projeto" maxlength="255" required> <br>
        <select name="responsavel" id="responsavel">
            <option value="" selected disabled>Selecione uma opção</option>
        </select>
    </form>
</body>
</html>