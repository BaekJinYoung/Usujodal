<!DOCTYPE html>
<html lang="ko">
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
@include('admin.components.head')
<body>

<div id="wrap">
    <div class="admin-container">
        <header id="header">
            @include('admin.components.snb')
        </header>
        <div class="admin-wrap">
            @if ($errors->any())
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="title-wrap col-group">
                <h2 class="main-title">
                    연혁 상세
                </h2>
            </div>
            <form id="historyForm" action="{{ route('admin.historyUpdate', $item) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="form-wrap row-group">
                    <div class="form-item row-group">
                        <p class="item-default">
                            진행 일자
                            <span class="red">*</span>
                        </p>
                        <input type="month" class="form-input w-560" id="date" name="date"
                               value="{{ old('date', date('Y-m', strtotime($item->date))) }}">
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            내용
                            <span class="red">*</span>
                        </p>
                        <textarea rows="5" name="content" id="content"
                                  placeholder="내용을 작성해주세요.">{{ old('content', $item->content) }}</textarea>
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            이미지
                        </p>
                        <div class="file-upload-wrap">
                            <input type='file' id='image_upload' accept="image/*" name="image" style="display: none;">
                            <label for="image_upload" class="file-upload-btn">
                                파일 업로드
                            </label>
                            <div class="file-preview" id="image-preview"
                                 @if(!$item->image) style="display: none" @endif>
                                <p class="file-name" id="image-filename">
                                    @if($item->image)
                                        {{$item->image_name}}
                                    @endif
                                </p>
                                <button type="button" class="file-del-btn" id="remove-image-btn">
                                    <i class="xi-close"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="remove_image" id="remove_image" value="0">
                </div>

                <div class="form-btn-wrap col-group">
                    <a href="{{ route('admin.historyIndex') }}" class="form-prev-btn">
                        목록으로
                    </a>
                    <button class="form-prev-btn" type="submit">
                        수정
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('date').addEventListener('change', function (event) {
        const selectedDate = new Date(event.target.value);
        const selectedYear = selectedDate.getFullYear();

        fetch(`/admin/history/check-image/${selectedYear}`)
            .then(response => response.json())
            .then(data => {
                const imagePreview = document.getElementById('image-preview');
                const imageFilename = document.getElementById('image-filename');

                if (data.exists) {
                    imagePreview.style.display = 'block';
                    imageFilename.textContent = data.imageName;
                } else {
                    imagePreview.style.display = 'none';
                    imageFilename.textContent = '';
                }
            })
            .catch(error => console.error('Error:', error));
    });


    document.getElementById('image_upload').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            if (confirm('이미지가 이미 등록되어 있습니다. 이미지를 덮어쓰시겠습니까?')) {
                document.getElementById('image-preview').style.display = 'block';
                document.getElementById('image-filename').textContent = file.name;
                document.querySelector('input[name="confirm_overwrite"]').value = 'yes';
            } else {
                document.getElementById('image_upload').value = '';
            }
        }
    });

    document.getElementById('remove-image-btn').addEventListener('click', function () {
        document.getElementById('image_upload').value = '';
        document.getElementById('image-preview').style.display = 'none';
        document.getElementById('remove_image').value = '1';
    });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
