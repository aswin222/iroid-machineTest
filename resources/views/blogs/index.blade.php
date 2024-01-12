<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Create a New Blog</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script src="{{ asset('path/to/ckeditor/ckeditor.js') }}"></script>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        form {
            display: grid;
            gap: 10px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #4caf50;
            color: #fff;
        }

        img {
            max-width: 100px;
            height: auto;
        }

        .delete-btn {
            background-color: #ff3333;
            color: #fff;
            padding: 8px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: #cc0000;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Create a New Blog</h2>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <form id="postForm" enctype="multipart/form-data">
            @csrf
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="date">Date:</label>
            <input type="date" name="date" id="date" required>

            <label for="author">Author:</label>
            <input type="text" name="author" id="author" required>

            <label for="content">Content:</label>
            <textarea id="editor" name="content"></textarea>

            <label for="image">Image:</label>
            <input type="file" name="image" id="image" accept="image/*" required>

            <button type="button" onclick="savePost()">Save</button>


        </form>
    </div>

    <h2>All blogs</h2>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th>Author</th>
                <th>Content</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($blogs as $blog)
            <tr>
                <td>{{ $blog->name }}</td>
                <td>{{ $blog->date }}</td>
                <td>{{ $blog->author }}</td>
                <td>{{ $blog->content }}</td>
                <td><img src="{{ Storage::url($blog->image) }}" alt="Blog Image"></td>

                <td>
                    <button class="delete-btn" data-id="{{ $blog->id }}">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            CKEDITOR.replace('editor');
        });

        function savePost() {
            let formData = new FormData($('#postForm')[0]);

            $.ajax({
                url: '{{ route('blogs.store') }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    location.reload();
                },
                error: function(error) {
                    console.error(error.responseJSON);
                }
            });
        }

        $(document).ready(function() {

            $('.delete-btn').on('click', function() {
                let blogId = $(this).data('id');
                deleteBlog(blogId);
            });
        });

        function deleteBlog(blogId) {
            $.ajax({
                url: '/blogs/' + blogId,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    console.log(data);
                    location.reload();
                },
                error: function(error) {
                    console.error(error.responseJSON);
                }
            });
        }
    </script>
</body>

</html>