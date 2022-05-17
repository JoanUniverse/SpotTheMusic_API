<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help</title>
    <style>
        @charset "UTF-8";
        @import url(https://fonts.googleapis.com/css?family=Open+Sans:300,400,700);

        body {
        font-family: 'Open Sans', sans-serif;
        font-weight: 700;
        line-height: 1.42em;
        color:#a4ceeb;
        background-color: #050326;
        }

        h1 {
        font-size:3em; 
        font-weight: 400;
        line-height:1em;
        text-align: center;
        color: #4DC3FA;
        padding-bottom: 1em;

        }

        h2 {
        font-size:2em; 
        font-weight: 400;
        text-align: center;
        display: block;
        line-height:1em;
        color: #FB667A;
        margin-top: 50px;
        }

        /* td, table, th {
            border: 3px solid #8ec4e8;
            border-collapse: collapse;
            border-spacing: 0;
        } */
        td {
            padding: 8px;
        }

        /* th {
            text-align: start;
        } */

        td, table, th {
            border-bottom: 2px solid white;
            border-collapse: collapse;
            border-spacing: 0;
        }

        table {
            border-radius: 50%;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        tbody {
            border-right: 2px solid white;
            border-left: 2px solid white;
        }

        span {
            color: #f0b343;
        }

    </style>
</head>
<body>
    <h1>API Endpoints</h1>
    <h2>Category</h2>
    <table>
        <thead>
            <tr>
                <th>Endpoint</th>
                <th>Method</th>
                <th>Body parameters</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>api/categories</td>
                <td>GET</td>
                <td>-</td>
                <td>Gets all categories</td>
            </tr>
            <tr>
                <td>api/categories</td>
                <td>POST</td>
                <td>name</td>
                <td>Creates one category</td>
            </tr>
            <tr>
                <td>api/categories/<span>{id}</span></td>
                <td>GET</td>
                <td>-</td>
                <td>Gets one category</td>
            </tr>
            <tr>
                <td>api/categories/<span>{id}</span></td>
                <td>DELETE</td>
                <td>-</td>
                <td>Deletes one category</td>
            </tr>
            <tr>
                <td>api/categories/<span>{id}</span></td>
                <td>PUT</td>
                <td>name, popularity</td>
                <td>Modifies one category</td>
            </tr>
        </tbody>
    </table>

    <h2>Post</h2>
    <table>
        <thead>
            <tr>
                <th>Endpoint</th>
                <th>Method</th>
                <th>Body parameters</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>api/posts</td>
                <td>GET</td>
                <td>-</td>
                <td>Get all categories</td>
            </tr>
            <tr>
                <td>api/posts</td>
                <td>POST</td>
                <td>-</td>
                <td>Get all categories</td>
            </tr>
            <tr>
                <td>api/posts/<span>{id}</span></td>
                <td>GET</td>
                <td>-</td>
                <td>Get all categories</td>
            </tr>
            <tr>
                <td>api/posts/{id}</td>
                <td>DELETE</td>
                <td>-</td>
                <td>Get all categories</td>
            </tr>
            <tr>
                <td>api/posts/{id}</td>
                <td>PUT</td>
                <td>-</td>
                <td>Get all categories</td>
            </tr>
        </tbody>
    </table>
</body>
</html>