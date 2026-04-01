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
        <select name="equipe" id="equipe">
            <option value="" selected disabled>Selecione uma opção (equipes)</option>
            <option value="Gerente de Projeto">Gerente de Projeto</option>
            <option value="Design">Design</option>
            <option value="Desenvolvimento">Desenvolvimento</option>
        </select> <br>
        <input type="text" placeholder="prioridade" name="prioridade" maxlength="255" > <br>
        <input type="text" placeholder="status" name="status" maxlength="255" > <br>
        <input type="text" placeholder="projeto" name="projeto" maxlength="255" required> <br>
        
        <select name="responsavel" id="responsavel">
            <option value="" selected disabled>Selecione um responsável pelo projeto</option>
            @foreach ($users as $index => $user)
                <option value="{{$index}}">{{$user->name}}</option>
            @endforeach
        </select>
    </form>
</body>
</html>