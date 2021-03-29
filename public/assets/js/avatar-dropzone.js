let avatarDropzone=function(a,b,c,d){const e=document.querySelector(a),f=window.Laravel.lang,g=document.querySelector(a).querySelector(".previews-container"),h=e.querySelector(".avatar-preview").src,i=e.querySelector(".avatar-preview-template"),j=e.querySelector(".avatar-preview"),k=e.querySelector(".avatar-pick"),l=e.querySelector(".avatar-path");let m=null;"ru"===f?m=dropzoneRU:"kk"===f&&(m=dropzoneKK);new Dropzone(a,{url:b,previewTemplate:i.innerHTML,maxFilesize:c,acceptedFiles:d,lastFile:null,previewsContainer:g,clickable:[k,j],init:function(){this.on("success",function(a,b){j.src=a.dataURL,l.value=b.location}),this.on("removedfile",function(){j.src=h,l.value=""}),this.on("addedfile",function(a){this.lastFile&&this.removeFile(this.lastFile),this.lastFile=a})},...m})};
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImF2YXRhci1kcm9wem9uZS5qcyJdLCJuYW1lcyI6WyJhdmF0YXJEcm9wem9uZSIsImlkIiwidXJsIiwibWF4U2l6ZSIsImFjY2VwdGVkRmlsZXMiLCJwYXJlbnQiLCJkb2N1bWVudCIsInF1ZXJ5U2VsZWN0b3IiLCJsYW5nIiwid2luZG93IiwiTGFyYXZlbCIsInByZXZpZXdzQ29udGFpbmVyIiwiZGVmYXVsdFByZXZpZXdVcmwiLCJzcmMiLCJwcmV2aWV3VGVtcGxhdGUiLCJhdmF0YXJQcmV2aWV3IiwiYXZhdGFyUGljayIsImF2YXRhclBhdGgiLCJsb2NhbGVPcHRpb25zIiwiZHJvcHpvbmVSVSIsImRyb3B6b25lS0siLCJEcm9wem9uZSIsImlubmVySFRNTCIsIm1heEZpbGVzaXplIiwibGFzdEZpbGUiLCJjbGlja2FibGUiLCJpbml0Iiwib24iLCJmaWxlIiwicmVzcG9uc2UiLCJkYXRhVVJMIiwidmFsdWUiLCJsb2NhdGlvbiIsInJlbW92ZUZpbGUiXSwibWFwcGluZ3MiOiJBQUFBLEdBQUlBLENBQUFBLGNBQWMsQ0FBRyxTQUFVQyxDQUFWLENBQWNDLENBQWQsQ0FBbUJDLENBQW5CLENBQTRCQyxDQUE1QixDQUEyQyxDQUM5RCxLQUFNQyxDQUFBQSxDQUFNLENBQUdDLFFBQVEsQ0FBQ0MsYUFBVCxDQUF1Qk4sQ0FBdkIsQ0FBZixDQUNFTyxDQUFJLENBQUdDLE1BQU0sQ0FBQ0MsT0FBUCxDQUFlRixJQUR4QixDQUVFRyxDQUFpQixDQUFHTCxRQUFRLENBQUNDLGFBQVQsQ0FBdUJOLENBQXZCLEVBQTJCTSxhQUEzQixDQUF5QyxxQkFBekMsQ0FGdEIsQ0FHRUssQ0FBaUIsQ0FBR1AsQ0FBTSxDQUFDRSxhQUFQLENBQXFCLGlCQUFyQixFQUF3Q00sR0FIOUQsQ0FJRUMsQ0FBZSxDQUFHVCxDQUFNLENBQUNFLGFBQVAsQ0FBcUIsMEJBQXJCLENBSnBCLENBS0VRLENBQWEsQ0FBR1YsQ0FBTSxDQUFDRSxhQUFQLENBQXFCLGlCQUFyQixDQUxsQixDQU1FUyxDQUFVLENBQUdYLENBQU0sQ0FBQ0UsYUFBUCxDQUFxQixjQUFyQixDQU5mLENBT0VVLENBQVUsQ0FBR1osQ0FBTSxDQUFDRSxhQUFQLENBQXFCLGNBQXJCLENBUGYsQ0FRQSxHQUFJVyxDQUFBQSxDQUFhLENBQUcsSUFBcEIsQ0FHYSxJQUFULEdBQUFWLENBWjBELENBYTVEVSxDQUFhLENBQUdDLFVBYjRDLENBYzFDLElBQVQsR0FBQVgsQ0FkbUQsR0FlNURVLENBQWEsQ0FBR0UsVUFmNEMsRUFrQjVDLEdBQUlDLENBQUFBLFFBQUosQ0FBYXBCLENBQWIsQ0FBaUIsQ0FDakNDLEdBQUcsQ0FBRUEsQ0FENEIsQ0FFakNZLGVBQWUsQ0FBRUEsQ0FBZSxDQUFDUSxTQUZBLENBR2pDQyxXQUFXLENBQUVwQixDQUhvQixDQUlqQ0MsYUFBYSxDQUFFQSxDQUprQixDQUtqQ29CLFFBQVEsQ0FBRSxJQUx1QixDQU1qQ2IsaUJBQWlCLENBQUVBLENBTmMsQ0FPakNjLFNBQVMsQ0FBRSxDQUFDVCxDQUFELENBQWFELENBQWIsQ0FQc0IsQ0FRakNXLElBQUksQ0FBRSxVQUFZLENBQ2hCLEtBQUtDLEVBQUwsQ0FBUSxTQUFSLENBQW1CLFNBQVVDLENBQVYsQ0FBZ0JDLENBQWhCLENBQTBCLENBQzNDZCxDQUFhLENBQUNGLEdBQWQsQ0FBb0JlLENBQUksQ0FBQ0UsT0FEa0IsQ0FFM0NiLENBQVUsQ0FBQ2MsS0FBWCxDQUFtQkYsQ0FBUSxDQUFDRyxRQUM3QixDQUhELENBRGdCLENBTWhCLEtBQUtMLEVBQUwsQ0FBUSxhQUFSLENBQXVCLFVBQVksQ0FDakNaLENBQWEsQ0FBQ0YsR0FBZCxDQUFvQkQsQ0FEYSxDQUVqQ0ssQ0FBVSxDQUFDYyxLQUFYLENBQW1CLEVBQ3BCLENBSEQsQ0FOZ0IsQ0FXaEIsS0FBS0osRUFBTCxDQUFRLFdBQVIsQ0FBcUIsU0FBVUMsQ0FBVixDQUFnQixDQUMvQixLQUFLSixRQUQwQixFQUVqQyxLQUFLUyxVQUFMLENBQWdCLEtBQUtULFFBQXJCLENBRmlDLENBSW5DLEtBQUtBLFFBQUwsQ0FBZ0JJLENBQ2pCLENBTEQsQ0FNRCxDQXpCZ0MsQ0EwQmpDLEdBQUdWLENBMUI4QixDQUFqQixDQTRCbkIsQ0E5Q0QiLCJzb3VyY2VzQ29udGVudCI6WyJsZXQgYXZhdGFyRHJvcHpvbmUgPSBmdW5jdGlvbiAoaWQsIHVybCwgbWF4U2l6ZSwgYWNjZXB0ZWRGaWxlcykge1xyXG4gIGNvbnN0IHBhcmVudCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoaWQpLFxyXG4gICAgbGFuZyA9IHdpbmRvdy5MYXJhdmVsLmxhbmcsXHJcbiAgICBwcmV2aWV3c0NvbnRhaW5lciA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoaWQpLnF1ZXJ5U2VsZWN0b3IoJy5wcmV2aWV3cy1jb250YWluZXInKSxcclxuICAgIGRlZmF1bHRQcmV2aWV3VXJsID0gcGFyZW50LnF1ZXJ5U2VsZWN0b3IoJy5hdmF0YXItcHJldmlldycpLnNyYyxcclxuICAgIHByZXZpZXdUZW1wbGF0ZSA9IHBhcmVudC5xdWVyeVNlbGVjdG9yKCcuYXZhdGFyLXByZXZpZXctdGVtcGxhdGUnKSxcclxuICAgIGF2YXRhclByZXZpZXcgPSBwYXJlbnQucXVlcnlTZWxlY3RvcignLmF2YXRhci1wcmV2aWV3JyksXHJcbiAgICBhdmF0YXJQaWNrID0gcGFyZW50LnF1ZXJ5U2VsZWN0b3IoJy5hdmF0YXItcGljaycpLFxyXG4gICAgYXZhdGFyUGF0aCA9IHBhcmVudC5xdWVyeVNlbGVjdG9yKCcuYXZhdGFyLXBhdGgnKTtcclxuICBsZXQgbG9jYWxlT3B0aW9ucyA9IG51bGw7XHJcblxyXG4gIC8qQ2hlY2sgaWYgbGFuZyBpcyBub3QgZW5nKi9cclxuICBpZiAobGFuZyA9PT0gJ3J1Jykge1xyXG4gICAgbG9jYWxlT3B0aW9ucyA9IGRyb3B6b25lUlU7XHJcbiAgfSBlbHNlIGlmIChsYW5nID09PSAna2snKSB7XHJcbiAgICBsb2NhbGVPcHRpb25zID0gZHJvcHpvbmVLS1xyXG4gIH1cclxuXHJcbiAgbGV0IG5ld0Ryb3B6b25lID0gbmV3IERyb3B6b25lKGlkLCB7XHJcbiAgICB1cmw6IHVybCxcclxuICAgIHByZXZpZXdUZW1wbGF0ZTogcHJldmlld1RlbXBsYXRlLmlubmVySFRNTCxcclxuICAgIG1heEZpbGVzaXplOiBtYXhTaXplLFxyXG4gICAgYWNjZXB0ZWRGaWxlczogYWNjZXB0ZWRGaWxlcyxcclxuICAgIGxhc3RGaWxlOiBudWxsLFxyXG4gICAgcHJldmlld3NDb250YWluZXI6IHByZXZpZXdzQ29udGFpbmVyLFxyXG4gICAgY2xpY2thYmxlOiBbYXZhdGFyUGljaywgYXZhdGFyUHJldmlld10sXHJcbiAgICBpbml0OiBmdW5jdGlvbiAoKSB7XHJcbiAgICAgIHRoaXMub24oJ3N1Y2Nlc3MnLCBmdW5jdGlvbiAoZmlsZSwgcmVzcG9uc2UpIHtcclxuICAgICAgICBhdmF0YXJQcmV2aWV3LnNyYyA9IGZpbGUuZGF0YVVSTDtcclxuICAgICAgICBhdmF0YXJQYXRoLnZhbHVlID0gcmVzcG9uc2UubG9jYXRpb247XHJcbiAgICAgIH0pO1xyXG5cclxuICAgICAgdGhpcy5vbigncmVtb3ZlZGZpbGUnLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgYXZhdGFyUHJldmlldy5zcmMgPSBkZWZhdWx0UHJldmlld1VybDtcclxuICAgICAgICBhdmF0YXJQYXRoLnZhbHVlID0gJyc7XHJcbiAgICAgIH0pO1xyXG5cclxuICAgICAgdGhpcy5vbignYWRkZWRmaWxlJywgZnVuY3Rpb24gKGZpbGUpIHtcclxuICAgICAgICBpZiAodGhpcy5sYXN0RmlsZSkge1xyXG4gICAgICAgICAgdGhpcy5yZW1vdmVGaWxlKHRoaXMubGFzdEZpbGUpXHJcbiAgICAgICAgfVxyXG4gICAgICAgIHRoaXMubGFzdEZpbGUgPSBmaWxlO1xyXG4gICAgICB9KVxyXG4gICAgfSxcclxuICAgIC4uLmxvY2FsZU9wdGlvbnNcclxuICB9KTtcclxufTsiXSwiZmlsZSI6ImF2YXRhci1kcm9wem9uZS5qcyJ9
