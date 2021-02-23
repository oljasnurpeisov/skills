(function(){function a(a){hideAllChildren(h),showEl(a),b(h),c(a)}function b(a){a.querySelectorAll(".required").forEach(function(a){a.removeAttribute("required")})}function c(a){a.querySelectorAll(".required").forEach(function(a){a.setAttribute("required","required")})}const d=document.querySelector("#lessonSelect"),f=document.querySelector("#practiceTypes"),g=document.querySelectorAll("[name=\"practiceType\"]"),h=document.querySelector("#optionalFields"),i=document.querySelector("#homework"),j=document.querySelector("#test");d.onchange=function(c){let d=c.target.value,e=returnCheckedRadio(g).value;"practice"===d?(showEl(f),"test"===e?a(j):"homework"===e&&a(i)):(hideEl(f),hideAllChildren(h),b(h))},g.forEach(function(b){b.addEventListener("change",function(b){let c=document.querySelector("#"+b.target.value);a(c)})}),htmlCollectionToArray(document.querySelector("#optionalFields").children).forEach(function(a){a.querySelectorAll("[required]").forEach(function(b){b.classList.add("required"),"none"===a.style.display&&b.removeAttribute("required")})})})();
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImxlc3Nvbi1jcmVhdGUuanMiXSwibmFtZXMiOlsic2hvd1RhYiIsInRhYiIsImhpZGVBbGxDaGlsZHJlbiIsInRhYnNXcmFwcGVyIiwic2hvd0VsIiwicmVtb3ZlUmVxdWlyZWRGaWVsZHMiLCJzZXRSZXF1aXJlZEZpZWxkcyIsInBhcmVudCIsInF1ZXJ5U2VsZWN0b3JBbGwiLCJmb3JFYWNoIiwiZWwiLCJyZW1vdmVBdHRyaWJ1dGUiLCJzZXRBdHRyaWJ1dGUiLCJsZXNzb25TZWxlY3QiLCJkb2N1bWVudCIsInF1ZXJ5U2VsZWN0b3IiLCJwcmFjdGljZVR5cGVzSW5wdXRzIiwicHJhY3RpY2VUeXBlUmFkaW9zIiwiaG9tZXdvcmtUYWIiLCJ0ZXN0VGFiIiwib25jaGFuZ2UiLCJlIiwidmFsdWUiLCJ0YXJnZXQiLCJwcmFjdGljZVR5cGVWYWx1ZSIsInJldHVybkNoZWNrZWRSYWRpbyIsImhpZGVFbCIsInJhZGlvIiwiYWRkRXZlbnRMaXN0ZW5lciIsImh0bWxDb2xsZWN0aW9uVG9BcnJheSIsImNoaWxkcmVuIiwiY2xhc3NMaXN0IiwiYWRkIiwic3R5bGUiLCJkaXNwbGF5Il0sIm1hcHBpbmdzIjoiQUFBQSxDQUFDLFVBQVksQ0FvQ1gsUUFBU0EsQ0FBQUEsQ0FBVCxDQUFpQkMsQ0FBakIsQ0FBc0IsQ0FDcEJDLGVBQWUsQ0FBQ0MsQ0FBRCxDQURLLENBRXBCQyxNQUFNLENBQUNILENBQUQsQ0FGYyxDQUdwQkksQ0FBb0IsQ0FBQ0YsQ0FBRCxDQUhBLENBSXBCRyxDQUFpQixDQUFDTCxDQUFELENBQ2xCLENBYUQsUUFBU0ksQ0FBQUEsQ0FBVCxDQUE4QkUsQ0FBOUIsQ0FBc0MsQ0FDcENBLENBQU0sQ0FBQ0MsZ0JBQVAsQ0FBd0IsV0FBeEIsRUFBcUNDLE9BQXJDLENBQTZDLFNBQVVDLENBQVYsQ0FBYyxDQUN6REEsQ0FBRSxDQUFDQyxlQUFILENBQW1CLFVBQW5CLENBQ0QsQ0FGRCxDQUdELENBRUQsUUFBU0wsQ0FBQUEsQ0FBVCxDQUEyQkMsQ0FBM0IsQ0FBbUMsQ0FDakNBLENBQU0sQ0FBQ0MsZ0JBQVAsQ0FBd0IsV0FBeEIsRUFBcUNDLE9BQXJDLENBQTZDLFNBQVVDLENBQVYsQ0FBYyxDQUN6REEsQ0FBRSxDQUFDRSxZQUFILENBQWdCLFVBQWhCLENBQTRCLFVBQTVCLENBQ0QsQ0FGRCxDQUdELENBaEVVLEtBQ0xDLENBQUFBLENBQVksQ0FBR0MsUUFBUSxDQUFDQyxhQUFULENBQXVCLGVBQXZCLENBRFYsQ0FFVEMsQ0FBbUIsQ0FBR0YsUUFBUSxDQUFDQyxhQUFULENBQXVCLGdCQUF2QixDQUZiLENBR1RFLENBQWtCLENBQUdILFFBQVEsQ0FBQ04sZ0JBQVQsQ0FBMEIseUJBQTFCLENBSFosQ0FNTEwsQ0FBVyxDQUFHVyxRQUFRLENBQUNDLGFBQVQsQ0FBdUIsaUJBQXZCLENBTlQsQ0FPVEcsQ0FBVyxDQUFHSixRQUFRLENBQUNDLGFBQVQsQ0FBdUIsV0FBdkIsQ0FQTCxDQVFUSSxDQUFPLENBQUdMLFFBQVEsQ0FBQ0MsYUFBVCxDQUF1QixPQUF2QixDQVJELENBVVhGLENBQVksQ0FBQ08sUUFBYixDQUF3QixTQUFVQyxDQUFWLENBQWEsSUFDL0JDLENBQUFBLENBQUssQ0FBR0QsQ0FBQyxDQUFDRSxNQUFGLENBQVNELEtBRGMsQ0FFL0JFLENBQWlCLENBQUdDLGtCQUFrQixDQUFDUixDQUFELENBQWxCLENBQXVDSyxLQUY1QixDQUlyQixVQUFWLEdBQUFBLENBSitCLEVBS2pDbEIsTUFBTSxDQUFDWSxDQUFELENBTDJCLENBT1AsTUFBdEIsR0FBQVEsQ0FQNkIsQ0FRL0J4QixDQUFPLENBQUNtQixDQUFELENBUndCLENBU0EsVUFBdEIsR0FBQUssQ0FUc0IsRUFVL0J4QixDQUFPLENBQUNrQixDQUFELENBVndCLEdBYWpDUSxNQUFNLENBQUNWLENBQUQsQ0FiMkIsQ0FjakNkLGVBQWUsQ0FBQ0MsQ0FBRCxDQWRrQixDQWVqQ0UsQ0FBb0IsQ0FBQ0YsQ0FBRCxDQWZhLENBaUJwQyxDQTNCVSxDQTZCWGMsQ0FBa0IsQ0FBQ1IsT0FBbkIsQ0FBMkIsU0FBVWtCLENBQVYsQ0FBaUIsQ0FDMUNBLENBQUssQ0FBQ0MsZ0JBQU4sQ0FBdUIsUUFBdkIsQ0FBaUMsU0FBVVAsQ0FBVixDQUFhLENBQzVDLEdBQUlwQixDQUFBQSxDQUFHLENBQUdhLFFBQVEsQ0FBQ0MsYUFBVCxDQUF1QixJQUFNTSxDQUFDLENBQUNFLE1BQUYsQ0FBU0QsS0FBdEMsQ0FBVixDQUNBdEIsQ0FBTyxDQUFDQyxDQUFELENBQ1IsQ0FIRCxDQUlELENBTEQsQ0E3QlcsQ0EyQ1g0QixxQkFBcUIsQ0FBQ2YsUUFBUSxDQUFDQyxhQUFULENBQXVCLGlCQUF2QixFQUEwQ2UsUUFBM0MsQ0FBckIsQ0FBMEVyQixPQUExRSxDQUFrRixTQUFVUixDQUFWLENBQWUsQ0FFN0ZBLENBQUcsQ0FBQ08sZ0JBQUosQ0FBcUIsWUFBckIsRUFBbUNDLE9BQW5DLENBQTJDLFNBQVVDLENBQVYsQ0FBYyxDQUN2REEsQ0FBRSxDQUFDcUIsU0FBSCxDQUFhQyxHQUFiLENBQWlCLFVBQWpCLENBRHVELENBRzdCLE1BQXRCLEdBQUEvQixDQUFHLENBQUNnQyxLQUFKLENBQVVDLE9BSHlDLEVBSXJEeEIsQ0FBRSxDQUFDQyxlQUFILENBQW1CLFVBQW5CLENBRUgsQ0FORCxDQU9ILENBVEQsQ0FzQkQsQ0FqRUQsRyIsInNvdXJjZXNDb250ZW50IjpbIihmdW5jdGlvbiAoKSB7XHJcbiAgY29uc3QgbGVzc29uU2VsZWN0ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignI2xlc3NvblNlbGVjdCcpLFxyXG4gICAgcHJhY3RpY2VUeXBlc0lucHV0cyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJyNwcmFjdGljZVR5cGVzJyksXHJcbiAgICBwcmFjdGljZVR5cGVSYWRpb3MgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCdbbmFtZT1cInByYWN0aWNlVHlwZVwiXScpO1xyXG5cclxuICAvL1RhYnNcclxuICBjb25zdCB0YWJzV3JhcHBlciA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJyNvcHRpb25hbEZpZWxkcycpLFxyXG4gICAgaG9tZXdvcmtUYWIgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcjaG9tZXdvcmsnKSxcclxuICAgIHRlc3RUYWIgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcjdGVzdCcpO1xyXG5cclxuICBsZXNzb25TZWxlY3Qub25jaGFuZ2UgPSBmdW5jdGlvbiAoZSkge1xyXG4gICAgbGV0IHZhbHVlID0gZS50YXJnZXQudmFsdWU7XHJcbiAgICBsZXQgcHJhY3RpY2VUeXBlVmFsdWUgPSByZXR1cm5DaGVja2VkUmFkaW8ocHJhY3RpY2VUeXBlUmFkaW9zKS52YWx1ZTtcclxuXHJcbiAgICBpZiAodmFsdWUgPT09ICdwcmFjdGljZScpIHtcclxuICAgICAgc2hvd0VsKHByYWN0aWNlVHlwZXNJbnB1dHMpO1xyXG5cclxuICAgICAgaWYgKHByYWN0aWNlVHlwZVZhbHVlID09PSAndGVzdCcpIHtcclxuICAgICAgICBzaG93VGFiKHRlc3RUYWIpO1xyXG4gICAgICB9IGVsc2UgaWYgKHByYWN0aWNlVHlwZVZhbHVlID09PSAnaG9tZXdvcmsnKSB7XHJcbiAgICAgICAgc2hvd1RhYihob21ld29ya1RhYik7XHJcbiAgICAgIH1cclxuICAgIH0gZWxzZSB7XHJcbiAgICAgIGhpZGVFbChwcmFjdGljZVR5cGVzSW5wdXRzKTtcclxuICAgICAgaGlkZUFsbENoaWxkcmVuKHRhYnNXcmFwcGVyKTtcclxuICAgICAgcmVtb3ZlUmVxdWlyZWRGaWVsZHModGFic1dyYXBwZXIpO1xyXG4gICAgfVxyXG4gIH07XHJcblxyXG4gIHByYWN0aWNlVHlwZVJhZGlvcy5mb3JFYWNoKGZ1bmN0aW9uIChyYWRpbykge1xyXG4gICAgcmFkaW8uYWRkRXZlbnRMaXN0ZW5lcignY2hhbmdlJywgZnVuY3Rpb24gKGUpIHtcclxuICAgICAgbGV0IHRhYiA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJyMnICsgZS50YXJnZXQudmFsdWUpO1xyXG4gICAgICBzaG93VGFiKHRhYik7XHJcbiAgICB9KTtcclxuICB9KTtcclxuXHJcbiAgZnVuY3Rpb24gc2hvd1RhYih0YWIpIHtcclxuICAgIGhpZGVBbGxDaGlsZHJlbih0YWJzV3JhcHBlcik7XHJcbiAgICBzaG93RWwodGFiKTtcclxuICAgIHJlbW92ZVJlcXVpcmVkRmllbGRzKHRhYnNXcmFwcGVyKTtcclxuICAgIHNldFJlcXVpcmVkRmllbGRzKHRhYik7XHJcbiAgfVxyXG5cclxuICBodG1sQ29sbGVjdGlvblRvQXJyYXkoZG9jdW1lbnQucXVlcnlTZWxlY3RvcignI29wdGlvbmFsRmllbGRzJykuY2hpbGRyZW4pLmZvckVhY2goZnVuY3Rpb24gKHRhYikge1xyXG5cclxuICAgICAgdGFiLnF1ZXJ5U2VsZWN0b3JBbGwoJ1tyZXF1aXJlZF0nKS5mb3JFYWNoKGZ1bmN0aW9uIChlbCkge1xyXG4gICAgICAgIGVsLmNsYXNzTGlzdC5hZGQoJ3JlcXVpcmVkJyk7XHJcblxyXG4gICAgICAgIGlmICh0YWIuc3R5bGUuZGlzcGxheSA9PT0gJ25vbmUnKSB7XHJcbiAgICAgICAgICBlbC5yZW1vdmVBdHRyaWJ1dGUoJ3JlcXVpcmVkJyk7XHJcbiAgICAgICAgfVxyXG4gICAgICB9KTtcclxuICB9KTtcclxuXHJcbiAgZnVuY3Rpb24gcmVtb3ZlUmVxdWlyZWRGaWVsZHMocGFyZW50KSB7XHJcbiAgICBwYXJlbnQucXVlcnlTZWxlY3RvckFsbCgnLnJlcXVpcmVkJykuZm9yRWFjaChmdW5jdGlvbiAoZWwpIHtcclxuICAgICAgZWwucmVtb3ZlQXR0cmlidXRlKCdyZXF1aXJlZCcpO1xyXG4gICAgfSk7XHJcbiAgfVxyXG5cclxuICBmdW5jdGlvbiBzZXRSZXF1aXJlZEZpZWxkcyhwYXJlbnQpIHtcclxuICAgIHBhcmVudC5xdWVyeVNlbGVjdG9yQWxsKCcucmVxdWlyZWQnKS5mb3JFYWNoKGZ1bmN0aW9uIChlbCkge1xyXG4gICAgICBlbC5zZXRBdHRyaWJ1dGUoJ3JlcXVpcmVkJywgJ3JlcXVpcmVkJyk7XHJcbiAgICB9KTtcclxuICB9XHJcbn0pKCk7Il0sImZpbGUiOiJsZXNzb24tY3JlYXRlLmpzIn0=
