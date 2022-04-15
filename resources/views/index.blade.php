<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf" content="{{ csrf_token() }}">
    <title>Sample Response</title>

    <!-- bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- json viewer style and script -->
    <script src="{{ url('storage/assets/tools/json-viewer/jquery.json-viewer.js') }}"></script>
    <link rel="stylesheet" href="{{ url('storage/assets/tools/json-viewer/jquery.json-viewer.css') }}">

    <!-- datatable styles and scripts -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <style>
        #json-viewer {
            box-shadow: 2px 5px 41px 0px #6B6B6B;
        }
        #loader {
            pointer-events: all;
            z-index: 99999;
            border: none;
            margin: 0px;
            padding: 0px;
            width: 100%;
            height: 100%;
            top: 0px;
            left: 0px;
            cursor: wait;
            position: fixed;
            background-color: rgba(0, 0, 0, 0.6);
            display: none;
        }
    </style>
</head>
<body>

<header>
    <div class="pt-5 pb-5 text-center text-light" style="background-color: #782195">
        <h1>Sample Response Generator</h1>
        <p class="m-5">
            The simple and efficient tool for developers to simulate and test their applications.
        </p>
    </div>
</header>


<div class="container">
    <section class="mt-3">
        <h2>Getting Start</h2>
        <span>Make your first response!</span>
        <div class="form-group">
            <label class="mb-2 mt-4" for="json">enter json:</label>
            <textarea name="json" class="form-control" id="json" cols="30" rows="10">{"name":"John", "age":30, "car":null}</textarea>
        </div>

        <div class="form-group">
            <button id="btn-json-viewer" class="btn btn-success mt-2">Check and generate</button>
        </div>
        <hr>
        <strong id="result">Result:</strong>
        <br>
        <code class="mb-3" id="url"></code>
        <pre class="border rounded mt-5" id="json-viewer"></pre>
    </section>


    <section class="mt-3">
        <h2>Examples</h2>
        <span>You can choose one of our examples.</span>

        <div class="">
            <table id="examples" class="table table-striped" style="width:100%">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Usage</th>
                    <th>Url</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </section>
</div>

<div id="loader"></div>

<script>
    $(document).ready(function () {
        function loader(status) {
            $('#loader').css('display', status)
        }

        var json;
        $('#btn-json-viewer').click(function () {
            loader('block');
            try {
                json = JSON.parse($('#json').val());
            } catch (e) {
                return  alert("Your JSON is not valid...!");
            }

            $.post({
                url: "/new-json",
                type: "POST",
                data: {
                    _token: $('meta[name=csrf]').attr('content'),
                    json: json
                },
                success: function (res) {
                    $('html, body').animate({
                        scrollTop: $("#result").offset().top - 35
                    }, 100);
                    $('#json-viewer').jsonViewer(json, {
                        collapsed:false,
                        withQuotes:true,
                        withLinks:false,
                        rootCollapsable:false
                    });
                    $('#url').text(res.url);
                    loader('none')
                },
                error: function (xhr, status, error) {
                    alert(error);
                    loader('none')
                }
            });

        });

        $('#examples').DataTable({
            ajax: {
                url: "/examples",
                type: "POST",
                data: {
                    _token: $('meta[name=csrf]').attr('content'),
                },
            },
            columns: [
                { data: "title" },
                { data: "category" },
                { data: "usage" },
                { data: "url", render: function (url) { return `<a class="btn btn-outline-primary" target="_blank" href="` + url + `" >Test</a>` } },
            ],
            serverSide: true,
            processing: true
        });
    });
</script>
</body>
</html>
