$(document).ready(function () {

    var config = {
        source: {
            selector: '#source'
        },
        preview: {
            selector: '#preview',
            width: 400,
            height: 400
        },
        selection: {
            selector: '#selection',
            width: 200,
            height: 100
        }
    };
    init(config);
});

var original_image = {};
function init(config)
{
    config.original_image = {};
    config.preview_image = {};

    $(config.source.selector).change(function(event) {
        $.each(event.target.files, function(index, file) {
            var reader = new FileReader();
            reader.onload = function(event) {
                var image = new Image();
                image.onload = function() {

                    config.original_image.width = image.width;
                    config.original_image.height = image.height;

                    config.preview_image = resize(image, config.preview.width, config.preview.height);
                    //alert(JSON.stringify(config.preview_image));

                    config.original_image.width_ratio = config.original_image.width / config.preview_image.width;
                    config.original_image.height_ratio = config.original_image.height / config.preview_image.height;

                    //alert(config.preview_image.width+'x'+config.preview_image.height);
                    $(config.preview.selector)
                        .css('width', config.preview_image.width+'px')
                        .css('height', config.preview_image.height+'px')
                        .css('background-image', 'url('+config.preview_image.data+')');

                    var width_ratio = config.selection.width / image.width;
                    var height_ratio = config.selection.height / image.height;
                    var ratio = Math.min(width_ratio, height_ratio);

                    alert(config.original_image.width_ratio+' '+config.original_image.height_ratio+', '+width_ratio+' '+height_ratio);

                    var selection_scaled_width = Math.round(config.selection.width / config.original_image.width_ratio);
                    var selection_scaled_height = Math.round(config.selection.height / config.original_image.height_ratio);

                    //alert(selection_scaled_width+'x'+selection_scaled_height)

                    $(config.selection.selector)
                        .resizable({
                            containment: config.preview.selector,
                            aspectRatio: config.selection.width / config.selection.height,
                            minWidth: selection_scaled_width,
                            minHeight: selection_scaled_height,
                            stop: function (event, ui) {
                                //refreshOutputImage(ui.position.top, ui.position.left);
                            }
                        })
                        .css('width', selection_scaled_width+'px')
                        .css('height', selection_scaled_height+'px');

                };
                config.original_image.data = event.target.result;
                image.src = event.target.result;
            };
            reader.readAsDataURL(file);
        });
    });
}

function resize(image, width, height)
{
    var main_canvas = document.createElement("canvas");

    var width_ratio = width / image.width;
    var height_ratio = height / image.height;
    var ratio = Math.min(width_ratio, height_ratio);

    main_canvas.width = image.width * ratio;
    main_canvas.height = image.height * ratio;

    var ctx = main_canvas.getContext("2d");
    ctx.drawImage(image, 0, 0, main_canvas.width, main_canvas.height);
    var image = {
        data: main_canvas.toDataURL("image/png"),
        width: main_canvas.width,
        height: main_canvas.height,
        ratio: ratio
    };
    return image;
};