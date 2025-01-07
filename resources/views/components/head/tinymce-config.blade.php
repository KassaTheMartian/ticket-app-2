<div>
    {{-- <script src="https://cdn.tiny.cloud/1/{{ env('TINYMCE_API_KEY') }}/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> --}}
    <script src="/js/tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#content',
            menubar: false,  // Thêm dòng này để ẩn menubar
            width: '100%',
            height: '100%',
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools upload '
            ],
            toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media | forecolor backcolor emoticons',
            image_advtab: true,
            paste_data_images: true,  // Cho phép paste hình ảnh
            automatic_uploads: true,
            
            // Xử lý khi paste hoặc kéo thả file
            images_upload_handler: function (blobInfo, success, failure) {
                return new Promise((resolve, reject) => {  // Wrap trong Promise
                    var formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    formData.append('_token', '{{ csrf_token() }}');
                    
                    $.ajax({
                        url: '/attachments/upload',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            success(response.url);
                            resolve(response.url);  // Resolve Promise với URL
                        },
                        error: function(xhr, status, error) {
                            const errorMsg = 'Upload failed: ' + error;
                            failure(errorMsg);
                            reject(errorMsg);  // Reject Promise với error message
                        }
                    });
                });
            }
      });
  </script>
</div>