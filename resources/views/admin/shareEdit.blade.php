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
                    공지사항 상세
                </h2>
            </div>
            <form id="form" action="{{route("admin.shareUpdate", $item)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="form-wrap row-group">
                    <div class="form-item row-group">
                        <p class="item-default">
                            상단 공지
                            <span class="red">*</span>
                        </p>
                        <div class="radio-wrap">
                            <div class="label-wrap col-group">
                                <label for="radio_item_1" class="radio-item">
                                    <input type="radio" name="is_featured" id="radio_item_1" value="1" class="form-radio" {{ !$item->is_featured==0 ? 'checked' : '' }}>
                                    <div class="checked-item col-group">
                                        <span class="radio-icon"></span>
                                        Y
                                    </div>
                                </label>
                                <label for="radio_item_2" class="radio-item">
                                    <input type="radio" name="is_featured" id="radio_item_2" value="0" class="form-radio" {{ !$item->is_featured==1 ? 'checked' : '' }}>
                                    <div class="checked-item col-group">
                                        <span class="radio-icon"></span>
                                        N
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            제목
                            <span class="red">*</span>
                        </p>
                        <input type="text" name="title" class="form-input" id="title" value="{{old('title', $item->title)}}"
                               placeholder="제목을 작성해주세요.">
                    </div>
                    <div class="form-item editor row-group">
                        <p class="item-default">
                            내용
                            <span class="red">*</span>
                        </p>
                        <div id="editor"></div>
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            이미지
                            <span class="red">*</span>
                        </p>
                        <div class="file-upload-wrap">
                            <input type='file' id='pc_file_upload' accept="image/*" name="image"
                                   onchange="displayFileName(this, 'fileName')">
                            <label for="pc_file_upload" class="file-upload-btn">
                                파일 업로드
                            </label>
                            <div class="file-preview">
                                <p class="file-name" id="fileName">{{$item->image_name}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            첨부파일
                            <span class="red">*</span>
                        </p>
                        <div class="file-upload-wrap">
                            <input type='file' id='mb_file_upload' accept="image/*" name="file"
                                   onchange="displayFileName(this, 'mobile_fileName')">
                            <label for="mb_file_upload" class="file-upload-btn">
                                파일 업로드
                            </label>
                            <div class="file-preview">
                                <p class="file-name" id="mobile_fileName">{{$item->file_name}}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-btn-wrap col-group">
                    <a href="{{route("admin.shareIndex")}}" class="form-prev-btn">
                        목록으로
                    </a>
                    <button class="form-prev-btn" type="submit" id="submit">
                        수정
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    let toolbarOptions = [
        [{'size': ['small', false, 'large', 'huge']}],
        ['bold', 'italic', 'underline', 'strike'],
        ['image'],
        [{'align': []}, {'color': []}, {'background': []}],
        ['clean']
    ];

    let quill = new Quill('#editor', {
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });

    quill.root.innerHTML = `{!! $item->content !!}`;

    document.getElementById('form').addEventListener('submit', function (e) {
        let contentInput = document.createElement('input');
        contentInput.setAttribute('type', 'hidden');
        contentInput.setAttribute('name', 'content');
        contentInput.setAttribute('value', quill.root.innerHTML);
        this.appendChild(contentInput);
    });

    function displayFileName(input, fileNameElementId) {
        var fileName = input.files[0] ? input.files[0].name : '';
        document.getElementById(fileNameElementId).textContent = fileName;
    }
</script>

</body>
</html>
