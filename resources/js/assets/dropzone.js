let CustomDropzone = function (a, b, c, d, e) {
    const f = document.querySelector(a).querySelector(".previews-container"),
        g = document.querySelector(a).querySelector(".dropzone-default__link"), h = window.Laravel.lang;
    let i = [], j = null;
    "ru" === h ? j = dropzoneRU : "kk" === h && (j = dropzoneKK);
    new Dropzone(a, {
        url: b,
        paramName: "files[]",
        clickable: g,
        maxFiles: c,
        timeout: 18000,
        maxSize: d,
        acceptedFiles: e,
        previewsContainer: f,
        previewTemplate: `<div class="dz-preview dz-file-preview">
                            <div class="dz-details">
                                <div class="dz-filename"><span data-dz-name></span></div>
                                <div class="dz-size" data-dz-size></div>
                            </div>
                            <div class="alert alert-danger"><span data-dz-errormessage> </span></div>
                            <a href="javascript:undefined;" title="Удалить" class="author-picture__link red" data-dz-remove>Удалить</a>
                        </div>`,
        init: function () {
            this.on("success", (a, b) => {
                i.push(b.filenames + ""), this.element.nextElementSibling.value = JSON.stringify(i)
            })
        }, ...j
    })
};z
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImRyb3B6b25lLmpzIl0sIm5hbWVzIjpbIkN1c3RvbURyb3B6b25lIiwiaWQiLCJ1cmwiLCJtYXhGaWxlcyIsIm1heFNpemUiLCJhY2NlcHRlZEZpbGVzIiwicHJldmlld3NDb250YWluZXIiLCJkb2N1bWVudCIsInF1ZXJ5U2VsZWN0b3IiLCJjbGlja2FibGUiLCJsYW5nIiwid2luZG93IiwiTGFyYXZlbCIsImZpbGVuYW1lcyIsImxvY2FsZU9wdGlvbnMiLCJkcm9wem9uZVJVIiwiZHJvcHpvbmVLSyIsIkRyb3B6b25lIiwicGFyYW1OYW1lIiwicHJldmlld1RlbXBsYXRlIiwiaW5pdCIsIm9uIiwiZmlsZXMiLCJyZXNwb25zZSIsInB1c2giLCJlbGVtZW50IiwibmV4dEVsZW1lbnRTaWJsaW5nIiwidmFsdWUiLCJKU09OIiwic3RyaW5naWZ5Il0sIm1hcHBpbmdzIjoiQUFBQSxHQUFJQSxDQUFBQSxjQUFjLENBQUcsU0FBVUMsQ0FBVixDQUFjQyxDQUFkLENBQW1CQyxDQUFuQixDQUE2QkMsQ0FBN0IsQ0FBc0NDLENBQXRDLENBQXFELENBQ3hFLEtBQU1DLENBQUFBLENBQWlCLENBQUdDLFFBQVEsQ0FBQ0MsYUFBVCxDQUF1QlAsQ0FBdkIsRUFBMkJPLGFBQTNCLENBQXlDLHFCQUF6QyxDQUExQixDQUNFQyxDQUFTLENBQUdGLFFBQVEsQ0FBQ0MsYUFBVCxDQUF1QlAsQ0FBdkIsRUFBMkJPLGFBQTNCLENBQXlDLHlCQUF6QyxDQURkLENBRUVFLENBQUksQ0FBR0MsTUFBTSxDQUFDQyxPQUFQLENBQWVGLElBRnhCLENBR0EsR0FBSUcsQ0FBQUEsQ0FBUyxDQUFHLEVBQWhCLENBQ0VDLENBQWEsQ0FBRyxJQURsQixDQUlhLElBQVQsR0FBQUosQ0FSb0UsQ0FTdEVJLENBQWEsQ0FBR0MsVUFUc0QsQ0FVcEQsSUFBVCxHQUFBTCxDQVY2RCxHQVd0RUksQ0FBYSxDQUFHRSxVQVhzRCxFQWN0RCxHQUFJQyxDQUFBQSxRQUFKLENBQWFoQixDQUFiLENBQWlCLENBQ2pDQyxHQUFHLENBQUVBLENBRDRCLENBRWpDZ0IsU0FBUyxDQUFFLFNBRnNCLENBR2pDVCxTQUFTLENBQUVBLENBSHNCLENBSWpDTixRQUFRLENBQUVBLENBSnVCLENBS2pDQyxPQUFPLENBQUVBLENBTHdCLENBTWpDQyxhQUFhLENBQUVBLENBTmtCLENBT2pDQyxpQkFBaUIsQ0FBRUEsQ0FQYyxDQVFqQ2EsZUFBZSxDQUFHO0FBQ3RCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLCtCQWZxQyxDQWdCakNDLElBQUksQ0FBRSxVQUFZLENBQ2hCLEtBQUtDLEVBQUwsQ0FBUSxTQUFSLENBQW1CLENBQUNDLENBQUQsQ0FBUUMsQ0FBUixHQUFxQixDQUN0Q1YsQ0FBUyxDQUFDVyxJQUFWLENBQXNCRCxDQUFRLENBQUNWLFNBQS9CLElBRHNDLENBRXRDLEtBQUtZLE9BQUwsQ0FBYUMsa0JBQWIsQ0FBZ0NDLEtBQWhDLENBQXdDQyxJQUFJLENBQUNDLFNBQUwsQ0FBZWhCLENBQWYsQ0FDekMsQ0FIRCxDQUlELENBckJnQyxDQXNCakMsR0FBR0MsQ0F0QjhCLENBQWpCLENBd0JuQixDQXRDRCIsInNvdXJjZXNDb250ZW50IjpbImxldCBDdXN0b21Ecm9wem9uZSA9IGZ1bmN0aW9uIChpZCwgdXJsLCBtYXhGaWxlcywgbWF4U2l6ZSwgYWNjZXB0ZWRGaWxlcykge1xyXG4gIGNvbnN0IHByZXZpZXdzQ29udGFpbmVyID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihpZCkucXVlcnlTZWxlY3RvcignLnByZXZpZXdzLWNvbnRhaW5lcicpLFxyXG4gICAgY2xpY2thYmxlID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihpZCkucXVlcnlTZWxlY3RvcignLmRyb3B6b25lLWRlZmF1bHRfX2xpbmsnKSxcclxuICAgIGxhbmcgPSB3aW5kb3cuTGFyYXZlbC5sYW5nO1xyXG4gIGxldCBmaWxlbmFtZXMgPSBbXSxcclxuICAgIGxvY2FsZU9wdGlvbnMgPSBudWxsO1xyXG5cclxuICAvKkNoZWNrIGlmIGxhbmcgaXMgbm90IGVuZyovXHJcbiAgaWYgKGxhbmcgPT09ICdydScpIHtcclxuICAgIGxvY2FsZU9wdGlvbnMgPSBkcm9wem9uZVJVO1xyXG4gIH0gZWxzZSBpZiAobGFuZyA9PT0gJ2trJykge1xyXG4gICAgbG9jYWxlT3B0aW9ucyA9IGRyb3B6b25lS0tcclxuICB9XHJcblxyXG4gIGxldCBuZXdEcm9wem9uZSA9IG5ldyBEcm9wem9uZShpZCwge1xyXG4gICAgdXJsOiB1cmwsXHJcbiAgICBwYXJhbU5hbWU6IFwiZmlsZXNbXVwiLFxyXG4gICAgY2xpY2thYmxlOiBjbGlja2FibGUsXHJcbiAgICBtYXhGaWxlczogbWF4RmlsZXMsXHJcbiAgICBtYXhTaXplOiBtYXhTaXplLFxyXG4gICAgYWNjZXB0ZWRGaWxlczogYWNjZXB0ZWRGaWxlcyxcclxuICAgIHByZXZpZXdzQ29udGFpbmVyOiBwcmV2aWV3c0NvbnRhaW5lcixcclxuICAgIHByZXZpZXdUZW1wbGF0ZTogYDxkaXYgY2xhc3M9XCJkei1wcmV2aWV3IGR6LWZpbGUtcHJldmlld1wiPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cImR6LWRldGFpbHNcIj5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiZHotZmlsZW5hbWVcIj48c3BhbiBkYXRhLWR6LW5hbWU+PC9zcGFuPjwvZGl2PlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJkei1zaXplXCIgZGF0YS1kei1zaXplPjwvZGl2PlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiYWxlcnQgYWxlcnQtZGFuZ2VyXCI+PHNwYW4gZGF0YS1kei1lcnJvcm1lc3NhZ2U+IDwvc3Bhbj48L2Rpdj5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxhIGhyZWY9XCJqYXZhc2NyaXB0OnVuZGVmaW5lZDtcIiB0aXRsZT1cItCj0LTQsNC70LjRgtGMXCIgY2xhc3M9XCJhdXRob3ItcGljdHVyZV9fbGluayByZWRcIiBkYXRhLWR6LXJlbW92ZT7Qo9C00LDQu9C40YLRjDwvYT5cclxuICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+YCxcclxuICAgIGluaXQ6IGZ1bmN0aW9uICgpIHtcclxuICAgICAgdGhpcy5vbignc3VjY2VzcycsIChmaWxlcywgcmVzcG9uc2UpID0+IHtcclxuICAgICAgICBmaWxlbmFtZXMucHVzaChTdHJpbmcocmVzcG9uc2UuZmlsZW5hbWVzKSk7XHJcbiAgICAgICAgdGhpcy5lbGVtZW50Lm5leHRFbGVtZW50U2libGluZy52YWx1ZSA9IEpTT04uc3RyaW5naWZ5KGZpbGVuYW1lcylcclxuICAgICAgfSlcclxuICAgIH0sXHJcbiAgICAuLi5sb2NhbGVPcHRpb25zXHJcbiAgfSk7XHJcbn07Il0sImZpbGUiOiJkcm9wem9uZS5qcyJ9
