(function ($) {
    $(document).ready(function () {
        $.fn.uploadFile = function (type) {
            var blockText = {
                'img': {'text': ['Drag Image File Here'], 'name': ['img'], 'id': ['imguploadform']}
            };

            this.append('<p>' + blockText[type].text + '</p>');
            this.append('<input type="file" class="upload-file" name="' + type + 'file" id="' + type + 'uploadform" data-type="'+ blockText[type].name +'">');
            this.addClass('drag_n_drop--' + type + 'Path');
            $('input', this).hide();

            fileDropBlock(this, type);
        };

        var imgBlock = $('div', 'div[id$="_coverPath"]');
        imgBlock.uploadFile('img');

        $('input[type="file"]').on("change", function () {
            var $_this = $(this),
                type = $_this.data('type'),
                reader,
                file;
            file = this.files[0];

            if (window.FormData) {
                formdata = new FormData();
            }

            if (window.FileReader) {
                reader = new FileReader();
                reader.readAsDataURL(file);
            }

            if (formdata) {
                formdata.append("file", file);
            }

            if (!$.inArray(file.type, arrayType[type])) {
                $.ajax({
                    url: "/upload-file",
                    type: "POST",
                    data: formdata,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        var userData = jQuery.parseJSON(res);
                        $_this.parent().find('input[type="text"]').val(userData.filePath);
                    }
                });
            } else {
                alert('Wrong type')
            }
        });

        imgBlock.click(function () {
            fileLoadByDefault('imguploadform', 'img', this);
        });
    });
})(jQuery);