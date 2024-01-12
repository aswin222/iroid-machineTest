


    <div class="container">
        <h2>Edit Blog</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('blogs.update', $blog->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" class="form-control" value="{{ $blog->name }}" required>
            </div>

            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" name="date" class="form-control" value="{{ $blog->date }}" required>
            </div>

            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" name="author" class="form-control" value="{{ $blog->author }}" required>
            </div>

            <div class="form-group">
                <label for="content">Content:</label>
                <textarea name="content" class="form-control" required>{{ $blog->content }}</textarea>
            </div>

            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" name="image" class="form-control-file" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Update Blog</button>
        </form>
    </div>
