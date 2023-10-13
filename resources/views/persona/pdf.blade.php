<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .titulo {
            text-align: center;
            font-size: 24px;
            color: rgb(0, 0, 0);
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: rgb(113, 113, 196);
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .btn {
            display: inline-block;
            padding: 5px 10px;
            background-color: blue;
            color: white;
            text-decoration: none;
            border-radius: 3px;
        }

        .btn:hover {
            background-color: rgb(73, 73, 167);
        }

        .btn-danger {
            background-color: red;
        }

        .btn-danger:hover {
            background-color: darkred;
        }
    </style>
</head>

<body>
    <div>
        <h1 class="titulo">Listado de Usuarios</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Telefono</th>
                    <th>CI</th>
                    <th>Tipo de Usuario</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id ?? '--' }}</td>
                        <td>{{ $user->name ?? '--' }}</td>
                        <td>{{ $user->telefono ?? '--' }}</td>
                        <td>{{ $user->ci ?? '--' }}</td>
                        <td>{{ $user->tipo_usuario ?? '--' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
