<!DOCTYPE html>
<html lang="ko">
@include('admin.components.head')
<body>
<div id="wrap">
    <div class="admin-container">
        <header id="header">
            @include('admin.components.snb')
        </header>

        <div class="admin-wrap">

            <div class="title-wrap col-group">
                <h2 class="main-title">
                    공지사항 등록
                </h2>
            </div>
            <form enctype="multipart/form-data">
                <div class="form-wrap row-group">
                    <div class="form-item row-group">
                        <p class="item-default">
                            제목
                            <span class="red">*</span>
                        </p>
                        <input type="text" name="title" class="form-input" placeholder="제목을 입력하세요">
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            이미지
                            <span class="red">*</span>
                        </p>
                        <div class="file-upload-wrap">
                            <input type='file' id='popup_file' accept="image/*" name="image">
                            <label for="popup_file" class="file-upload-btn">
                                파일 업로드
                            </label>
                            <span class="guide-txt">
                                800*800px 비율 고해상도 사진 등록
                            </span>

                            <div class="file-preview" id="image">
                                <p class="file-name"></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-item row-group">
                        <p class="item-default">
                            링크(선택)
                        </p>
                        <input type="text" name="link" class="form-input" placeholder="링크를 입력하세요(https:// 포함), 선택사항">
                    </div>
                </div>
            </form>
            <div class="form-btn-wrap col-group">
                <a href="" class="form-prev-btn">
                    목록으로
                </a>
                <button class="form-prev-btn" type="submit">
                    등록
                </button>
                <button class="form-submit-btn" type="submit">
                    등록 후 계속
                </button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
