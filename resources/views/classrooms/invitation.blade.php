<html>
    <head>
        <link rel="stylesheet" data-href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&amp;display=swap">
    </head>
    <style>
        body, input, textarea, .button {
            font-family: 'Lato', sans-serif !important;
        }
        .header {
            margin-vertical: 16px;
        }
        .button {
            width: -moz-fit-content;
            width: fit-content;
            padding: 0.5rem 1.25rem;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            -webkit-transition: background-color .2s,color .2s;
            transition: background-color .2s,color .2s;
        }
        a {
            text-decoration: none;
        }
        .button-blue {
            background-color: #003049;
            color: #fff !important;
        }
    </style>
    <main>
        <p>Olá, você foi convidado a participar da turma {{$name}}.</p>
        <p>Seu código de acesso é {{$code}}.</p>
        <p>Para explorar o mundo encantado da geografia, clique no botão abaixo:</p>
        <a class="button-blue button" href="https://meg.vercel.app">Acessar</a>
    </main>
</html>
