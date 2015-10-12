(function( $ ) {
    $.fn.imageResizeAndCrop = function(customOptions) {
        var options = jQuery.extend({
            preview: { selector: '#preview', width: 500, height: 500 },
            selection: {
                selector: '#selection',
                width: 922,
                height: 519,
                showMessage: function (obj, width, height) {
                    alert("Image not valid. Min width: "+width+"px - Min height: "+height+"px");
                }
            },
            outputImage: { selector: '#output' }
        }, customOptions);

        var originalImage = {};
        var outputImage;

        var init = function (fileInput) {
            $(options.preview.selector).css('width', options.preview.width+'px').css('height', options.preview.height+'px');
            $(options.selection.selector)
                //.css('width', options.selection.width+'px')
                //.css('height', options.selection.height+'px')
                .draggable({
                    containment: options.preview.selector,
                    stop: function(event, ui) {
                        refreshOutputImage(ui.position.top, ui.position.left);
                    }
                });
//@todo si deve ingrandire l'area di selezione se e' troppo piccola in proporzione all'immagine e all'anteprima
            fileInput.change(function(event) {
                $.each(event.target.files, function(index, file) {
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        var image = new Image();
                        image.onload = function() {
                            originalImage.width = image.width;
                            originalImage.height = image.height;
                            var resizeImage = resize(image, options.preview.width, options.preview.height);
                            originalImage.widthRatio = originalImage.width / resizeImage.width;
                            originalImage.heightRatio = originalImage.height / resizeImage.height;
                            if (!isValid())
                            {
                                options.selection.showMessage(fileInput, options.selection.width, options.selection.height);
                                $(options.preview.selector+', '+options.outputImage.selector).hide();
                            }
                            else
                            {
                                $(options.preview.selector)
                                    .css('width', resizeImage.width+'px')
                                    .css('height', resizeImage.height+'px')
                                    .css('background-image', 'url('+resizeImage.data+')');

                                var selectionScaledWidth = Math.round(options.selection.width / originalImage.widthRatio);
                                var selectionScaledHeight = Math.round(options.selection.height / originalImage.heightRatio);
                                var selection = $(options.selection.selector);
                                selection
                                    .resizable({
                                        containment: options.preview.selector,
                                        aspectRatio: options.selection.width / options.selection.height,
                                        minWidth: selectionScaledWidth,
                                        minHeight: selectionScaledHeight,
                                        stop: function (event, ui) {
                                            refreshOutputImage(ui.position.top, ui.position.left);
                                        }
                                    })
                                    .css('width', selectionScaledWidth+'px')
                                    .css('height', selectionScaledHeight+'px');

                                var top = parseInt(selection.css('top'));
                                var left = parseInt(selection.css('left'));
                                refreshOutputImage(top, left);
                                $(options.preview.selector+', '+options.outputImage.selector).show();
                            }
                        };
                        originalImage.data = event.target.result;
                        image.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                });
            });
        };

        var refreshOutputImage = function (sy, sx)
        {

            if (sy < 0 || isNaN(sy)) sy = 0;
            if (sx < 0 || isNaN(sx)) sx = 0;
            swidth = Math.floor(parseInt($(options.selection.selector).width()) * originalImage.widthRatio);
            sheight = Math.floor(parseInt($(options.selection.selector).height()) * originalImage.heightRatio);

            alert(swidth+','+sheight+','+sx+','+sy);

            sy = Math.floor(sy * originalImage.heightRatio);
            sx = Math.floor(sx * originalImage.widthRatio);

            if (swidth < options.selection.width) swidth = options.selection.width;
            if (sheight < options.selection.height) sheight = options.selection.height;

            if (sx + swidth > originalImage.width)
            {
                sx = originalImage.width - swidth;
            }
            if (sy + sheight > originalImage.height)
            {
                sy = originalImage.height - sheight;
            }

            alert(swidth+','+sheight+','+sx+','+sy);

            var image = new Image();
            image.onload = function() {
                outputImage = clip(image, sx, sy, swidth, sheight, 0, 0, options.selection.width, options.selection.height);
                $(options.outputImage.selector).attr('src', outputImage.data);
                $('#output_str').html(outputImage.data);
            };
            image.src = originalImage.data;
        }

        var clip = function (image, sx, sy, swidth, sheight, x, y, width, height)
        {
            var mainCanvas = document.createElement("canvas");
            mainCanvas.width = width;
            mainCanvas.height = height;
            var ctx = mainCanvas.getContext("2d");
            ctx.drawImage(image, sx, sy, swidth, sheight, x, y, width, height);

            var image = {
                data: mainCanvas.toDataURL("image/jpeg"),
                width: mainCanvas.width,
                height: mainCanvas.height
            };
            return image;
        };

        var resize = function (image, width, height)
        {
            var mainCanvas = document.createElement("canvas");

            var widthRatio = width / image.width;
            var heightRatio = height / image.height;
            var ratio = Math.min(widthRatio, heightRatio);

            mainCanvas.width = image.width * ratio;
            mainCanvas.height = image.height * ratio;

            if (ratio > 1)
            {
                mainCanvas.width = image.width;
                mainCanvas.height = image.height;
            }

            var ctx = mainCanvas.getContext("2d");
            ctx.drawImage(image, 0, 0, mainCanvas.width, mainCanvas.height);
            var image = {
                data: mainCanvas.toDataURL("image/jpeg"),
                width: mainCanvas.width,
                height: mainCanvas.height,
                ratio: ratio
            };
            return image;
        };

        var isValid = function ()
        {
            return originalImage.width >= options.selection.width && originalImage.height >= options.selection.height;
        }

        this.getImageObject = function ()
        {
            return outputImage;
        };

        return this.each(function() {
            init($(this));
        });
    };
}( jQuery ));