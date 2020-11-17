let ajaxSelect=function(a,b,c=!0){this.domainName="https://dev3.panama.kz/",this.lang=window.Laravel.lang,this.el=a,this.url=this.domainName+this.lang+b,this.multiLang=c};ajaxSelect.prototype={initComponent:function(){function a(a,b,c,d){let e=[];b.forEach(function(a){e.push({value:a.id,text:a["name"+(c?"_"+d:"")]})}),a.addOption(e)}function b(a){let b=[];Object.values(a.options).forEach(a=>{b.push(a.value)}),a.setValue(b)}function c(a,b){a.setTextboxValue(b),a.focus()}let d=this.multiLang,e=this.lang,f=this.url,g=this.el.selectize({plugins:this.el[0].hasAttribute("multiple")?["remove_button"]:null,sortField:[{field:"text",direction:"asc"}],onInitialize:function(){$.ajax({type:"POST",url:f,data:{name:""},success:function(c){h.clearOptions(!0),b(h),a(h,c,d,e)},error:function(a){console.log(a)}})}}),h=g[0].selectize;h.on("type",delay(function(g){$.ajax({type:"POST",url:f,data:{name:g},success:function(f){h.clearOptions(!0),b(h),a(h,f,d,e),c(h,g)},error:function(a){console.log(a)}})},600))}};function delay(a,b){let c=0;return function(){let d=this,e=arguments;clearTimeout(c),c=setTimeout(function(){a.apply(d,e)},b||0)}}
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImFqYXgtc2VsZWN0LW9sZC5qcyJdLCJuYW1lcyI6WyJhamF4U2VsZWN0IiwiZWwiLCJtZXRob2QiLCJtdWx0aUxhbmciLCJkb21haW5OYW1lIiwibGFuZyIsIndpbmRvdyIsIkxhcmF2ZWwiLCJ1cmwiLCJwcm90b3R5cGUiLCJpbml0Q29tcG9uZW50IiwicmVuZGVyTmV3T3B0aW9ucyIsImNvbnRyb2xzIiwiZGF0YSIsIm5ld09wdGlvbnMiLCJmb3JFYWNoIiwiaXRlbSIsInB1c2giLCJ2YWx1ZSIsImlkIiwidGV4dCIsImFkZE9wdGlvbiIsInNhdmVPbGRPcHRpb25zIiwic2VsZWN0ZWRWYWx1ZXMiLCJPYmplY3QiLCJ2YWx1ZXMiLCJvcHRpb25zIiwic2V0VmFsdWUiLCJmb2N1c09uU2VhcmNoRmllbGQiLCJ2YWwiLCJzZXRUZXh0Ym94VmFsdWUiLCJmb2N1cyIsInNlbGVjdCIsInNlbGVjdGl6ZSIsInBsdWdpbnMiLCJoYXNBdHRyaWJ1dGUiLCJzb3J0RmllbGQiLCJmaWVsZCIsImRpcmVjdGlvbiIsIm9uSW5pdGlhbGl6ZSIsIiQiLCJhamF4IiwidHlwZSIsInN1Y2Nlc3MiLCJjbGVhck9wdGlvbnMiLCJlcnJvciIsImNvbnNvbGUiLCJsb2ciLCJvbiIsImRlbGF5IiwiY2FsbGJhY2siLCJtcyIsInRpbWVyIiwiY29udGV4dCIsImFyZ3MiLCJhcmd1bWVudHMiLCJjbGVhclRpbWVvdXQiLCJzZXRUaW1lb3V0IiwiYXBwbHkiXSwibWFwcGluZ3MiOiJBQUNBLEdBQUlBLENBQUFBLFVBQVUsQ0FBRyxTQUFVQyxDQUFWLENBQWNDLENBQWQsQ0FBc0JDLENBQVMsR0FBL0IsQ0FBd0MsQ0FDdkQsS0FBS0MsVUFBTCxDQUFrQix5QkFEcUMsQ0FFdkQsS0FBS0MsSUFBTCxDQUFZQyxNQUFNLENBQUNDLE9BQVAsQ0FBZUYsSUFGNEIsQ0FHdkQsS0FBS0osRUFBTCxDQUFVQSxDQUg2QyxDQUl2RCxLQUFLTyxHQUFMLENBQVcsS0FBS0osVUFBTCxDQUFrQixLQUFL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LENBSEQsQ0FNTGMsT0FBTyxDQUFFLFNBQVU5QixDQUFWLENBQWdCLENBQ3ZCRCxDQUFRLENBQUNnQyxZQUFULElBRHVCLENBR3ZCdEIsQ0FBYyxDQUFDVixDQUFELENBSFMsQ0FLdkJELENBQWdCLENBQUNDLENBQUQsQ0FBV0MsQ0FBWCxDQUFpQlYsQ0FBakIsQ0FBNEJFLENBQTVCLENBTE8sQ0FPdkJ1QixDQUFrQixDQUFDaEIsQ0FBRCxDQUFXaUIsQ0FBWCxDQUNuQixDQWRJLENBZUxnQixLQUFLLENBQUUsU0FBVWhDLENBQVYsQ0FBZ0IsQ0FDckJpQyxPQUFPLENBQUNDLEdBQVIsQ0FBWWxDLENBQVosQ0FDRCxDQWpCSSxDQUFQLENBbUJELENBcEJ3QixDQW9CdEIsR0FwQnNCLENBQXpCLENBNkNELENBbkZvQixDLENBdUZ2QixRQUFTb0MsQ0FBQUEsS0FBVCxDQUFlQyxDQUFmLENBQXlCQyxDQUF6QixDQUE2QixDQUMzQixHQUFJQyxDQUFBQSxDQUFLLENBQUcsQ0FBWixDQUNBLE1BQU8sV0FBWSxDQUNqQixHQUFJQyxDQUFBQSxDQUFPLENBQUcsSUFBZCxDQUFvQkMsQ0FBSSxDQUFHQyxTQUEzQixDQUNBQyxZQUFZLENBQUNKLENBQUQsQ0FGSyxDQUdqQkEsQ0FBSyxDQUFHSyxVQUFVLENBQUMsVUFBWSxDQUM3QlAsQ0FBUSxDQUFDUSxLQUFULENBQWVMLENBQWYsQ0FBd0JDLENBQXhCLENBQ0QsQ0FGaUIsQ0FFZkgsQ0FBRSxFQUFJLENBRlMsQ0FHbkIsQ0FDRiIsInNvdXJjZXNDb250ZW50IjpbIi8vQWpheCBzZWxlY3QgY29uc3RydWN0b3JcclxubGV0IGFqYXhTZWxlY3QgPSBmdW5jdGlvbiAoZWwsIG1ldGhvZCwgbXVsdGlMYW5nID0gdHJ1ZSkge1xyXG4gIHRoaXMuZG9tYWluTmFtZSA9ICdodHRwczovL2RldjMucGFuYW1hLmt6Lyc7XHJcbiAgdGhpcy5sYW5nID0gd2luZG93LkxhcmF2ZWwubGFuZztcclxuICB0aGlzLmVsID0gZWw7XHJcbiAgdGhpcy51cmwgPSB0aGlzLmRvbWFpbk5hbWUgKyB0aGlzLmxhbmcgKyBtZXRob2Q7XHJcbiAgdGhpcy5tdWx0aUxhbmcgPSBtdWx0aUxhbmc7XHJcbn07XHJcblxyXG5hamF4U2VsZWN0LnByb3RvdHlwZSA9IHtcclxuICBpbml0Q29tcG9uZW50OiBmdW5jdGlvbiAoKSB7XHJcbiAgICBsZXQgbXVsdGlMYW5nID0gdGhpcy5tdWx0aUxhbmcsXHJcbiAgICAgIGxhbmcgPSB0aGlzLmxhbmcsXHJcbiAgICAgIHVybCA9IHRoaXMudXJsO1xyXG5cclxuICAgIC8vSW5pdCBzZWxlY3RcclxuICAgIGxldCBzZWxlY3QgPSB0aGlzLmVsLnNlbGVjdGl6ZSh7XHJcbiAgICAgIHBsdWdpbnM6IHRoaXMuZWxbMF0uaGFzQXR0cmlidXRlKCdtdWx0aXBsZScpID8gWydyZW1vdmVfYnV0dG9uJ10gOiBudWxsLFxyXG4gICAgICBzb3J0RmllbGQ6IFtcclxuICAgICAgICB7XHJcbiAgICAgICAgICBmaWVsZDogJ3RleHQnLFxyXG4gICAgICAgICAgZGlyZWN0aW9uOiAnYXNjJ1xyXG4gICAgICAgIH1cclxuICAgICAgXSxcclxuICAgICAgb25Jbml0aWFsaXplOiBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgJC5hamF4KHtcclxuICAgICAgICAgIHR5cGU6ICdQT1NUJyxcclxuICAgICAgICAgIHVybDogdXJsLFxyXG4gICAgICAgICAgZGF0YToge1xyXG4gICAgICAgICAgICBcIm5hbWVcIjogJydcclxuICAgICAgICAgIH0sXHJcbiAgICAgICAgICBzdWNjZXNzOiBmdW5jdGlvbiAoZGF0YSkge1xyXG4gICAgICAgICAgICBjb250cm9scy5jbGVhck9wdGlvbnModHJ1ZSk7XHJcblxyXG4gICAgICAgICAgICBzYXZlT2xkT3B0aW9ucyhjb250cm9scyk7XHJcblxyXG4gICAgICAgICAgICByZW5kZXJOZXdPcHRpb25zKGNvbnRyb2xzLCBkYXRhLCBtdWx0aUxhbmcsIGxhbmcpO1xyXG4gICAgICAgICAgfSxcclxuICAgICAgICAgIGVycm9yOiBmdW5jdGlvbiAoZGF0YSkge1xyXG4gICAgICAgICAgICBjb25zb2xlLmxvZyhkYXRhKTtcclxuICAgICAgICAgIH1cclxuICAgICAgICB9KTtcclxuICAgICAgfVxyXG4gICAgfSk7XHJcbiAgICBsZXQgY29udHJvbHMgPSBzZWxlY3RbMF0uc2VsZWN0aXplO1xyXG5cclxuICAgIC8vT24gdHlwZSBldmVudFxyXG4gICAgY29udHJvbHMub24oJ3R5cGUnLCBkZWxheShmdW5jdGlvbiAodmFsKSB7XHJcbiAgICAgICQuYWpheCh7XHJcbiAgICAgICAgdHlwZTogJ1BPU1QnLFxyXG4gICAgICAgIHVybDogdXJsLFxyXG4gICAgICAgIGRhdGE6IHtcclxuICAgICAgICAgIFwibmFtZVwiOiB2YWxcclxuICAgICAgICB9LFxyXG4gICAgICAgIHN1Y2Nlc3M6IGZ1bmN0aW9uIChkYXRhKSB7XHJcbiAgICAgICAgICBjb250cm9scy5jbGVhck9wdGlvbnModHJ1ZSk7XHJcblxyXG4gICAgICAgICAgc2F2ZU9sZE9wdGlvbnMoY29udHJvbHMpO1xyXG5cclxuICAgICAgICAgIHJlbmRlck5ld09wdGlvbnMoY29udHJvbHMsIGRhdGEsIG11bHRpTGFuZywgbGFuZyk7XHJcblxyXG4gICAgICAgICAgZm9jdXNPblNlYXJjaEZpZWxkKGNvbnRyb2xzLCB2YWwpO1xyXG4gICAgICAgIH0sXHJcbiAgICAgICAgZXJyb3I6IGZ1bmN0aW9uIChkYXRhKSB7XHJcbiAgICAgICAgICBjb25zb2xlLmxvZyhkYXRhKTtcclxuICAgICAgICB9XHJcbiAgICAgIH0pO1xyXG4gICAgfSwgNjAwKSk7XHJcblxyXG4gICAgZnVuY3Rpb24gcmVuZGVyTmV3T3B0aW9ucyAoY29udHJvbHMsIGRhdGEsIG11bHRpTGFuZywgbGFuZykge1xyXG4gICAgICBsZXQgbmV3T3B0aW9ucyA9IFtdO1xyXG4gICAgICBkYXRhLmZvckVhY2goZnVuY3Rpb24gKGl0ZW0pIHtcclxuICAgICAgICBuZXdPcHRpb25zLnB1c2goe1xyXG4gICAgICAgICAgdmFsdWU6IGl0ZW0uaWQsXHJcbiAgICAgICAgICB0ZXh0OiBpdGVtW1wibmFtZVwiICsgKG11bHRpTGFuZyA/ICdfJyArIGxhbmcgOiAnJyldXHJcbiAgICAgICAgfSk7XHJcbiAgICAgIH0pO1xyXG4gICAgICBjb250cm9scy5hZGRPcHRpb24obmV3T3B0aW9ucyk7XHJcbiAgICB9XHJcblxyXG4gICAgZnVuY3Rpb24gc2F2ZU9sZE9wdGlvbnMgKGNvbnRyb2xzKSB7XHJcbiAgICAgIGxldCBzZWxlY3RlZFZhbHVlcyA9IFtdO1xyXG4gICAgICBPYmplY3QudmFsdWVzKGNvbnRyb2xzLm9wdGlvbnMpLmZvckVhY2goKGl0ZW0pID0+IHtcclxuICAgICAgICBzZWxlY3RlZFZhbHVlcy5wdXNoKGl0ZW0udmFsdWUpO1xyXG4gICAgICB9KTtcclxuICAgICAgY29udHJvbHMuc2V0VmFsdWUoc2VsZWN0ZWRWYWx1ZXMpO1xyXG4gICAgfVxyXG5cclxuICAgIGZ1bmN0aW9uIGZvY3VzT25TZWFyY2hGaWVsZCAoY29udHJvbHMsIHZhbCkge1xyXG4gICAgICBjb250cm9scy5zZXRUZXh0Ym94VmFsdWUodmFsKTtcclxuICAgICAgY29udHJvbHMuZm9jdXMoKTtcclxuICAgIH1cclxuICB9XHJcbn07XHJcblxyXG4vL0RlbGF5IGZ1bmN0aW9uXHJcbmZ1bmN0aW9uIGRlbGF5KGNhbGxiYWNrLCBtcykge1xyXG4gIGxldCB0aW1lciA9IDA7XHJcbiAgcmV0dXJuIGZ1bmN0aW9uICgpIHtcclxuICAgIGxldCBjb250ZXh0ID0gdGhpcywgYXJncyA9IGFyZ3VtZW50cztcclxuICAgIGNsZWFyVGltZW91dCh0aW1lcik7XHJcbiAgICB0aW1lciA9IHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xyXG4gICAgICBjYWxsYmFjay5hcHBseShjb250ZXh0LCBhcmdzKTtcclxuICAgIH0sIG1zIHx8IDApO1xyXG4gIH07XHJcbn1cclxuXHJcbi8vQWpheCBzZWxlY3RcclxuLypcclxubGV0IGFqYXhTZWxlY3QgPSBmdW5jdGlvbiAoZWwsIG1ldGhvZCwgcHJvZmVzc2lvbnNFbCA9IG51bGwpIHtcclxuICBjb25zdCBkb21haW5OYW1lID0gJ2h0dHBzOi8vZGV2My5wYW5hbWEua3ovJyxcclxuICAgIGxhbmcgPSB3aW5kb3cuTGFyYXZlbC5sYW5nO1xyXG5cclxuICBsZXQgc2VsZWN0ID0gZWwuc2VsZWN0aXplKHtcclxuICAgIHBsdWdpbnM6IGVsWzBdLmhhc0F0dHJpYnV0ZSgnbXVsdGlwbGUnKSA/IFsncmVtb3ZlX2J1dHRvbiddIDogbnVsbCxcclxuICAgIHNvcnRGaWVsZDogW1xyXG4gICAgICB7XHJcbiAgICAgICAgZmllbGQ6ICd0ZXh0JyxcclxuICAgICAgICBkaXJlY3Rpb246ICdhc2MnXHJcbiAgICAgIH1cclxuICAgIF0sXHJcbiAgICBvbkluaXRpYWxpemU6IGZ1bmN0aW9uICgpIHtcclxuICAgICAgbGV0IHByb2Zlc3Npb25zT2JqID0gcHJvZmVzc2lvbnNFbCA/IHtcInByb2Zlc3Npb25zXCI6IHByb2Zlc3Npb25zRWwudmFsKCkgPyBwcm9mZXNzaW9uc0VsLnZhbCgpIDogW119IDogbnVsbDtcclxuICAgICAgJC5hamF4KHtcclxuICAgICAgICB0eXBlOiAnUE9TVCcsXHJcbiAgICAgICAgdXJsOiBkb21haW5OYW1lICsgbGFuZyArIG1ldGhvZCxcclxuICAgICAgICBkYXRhOiB7XHJcbiAgICAgICAgICAuLi5wcm9mZXNzaW9uc09iaixcclxuICAgICAgICAgIFwibmFtZVwiOiAnJ1xyXG4gICAgICAgIH0sXHJcbiAgICAgICAgc3VjY2VzczogZnVuY3Rpb24gKGRhdGEpIHtcclxuICAgICAgICAgIGxldCBuZXdPcHRpb25zID0gW107XHJcbiAgICAgICAgICBkYXRhLmZvckVhY2goZnVuY3Rpb24gKGl0ZW0pIHtcclxuICAgICAgICAgICAgbmV3T3B0aW9ucy5wdXNoKHtcclxuICAgICAgICAgICAgICB2YWx1ZTogaXRlbS5pZCxcclxuICAgICAgICAgICAgICB0ZXh0OiBpdGVtW1wibmFtZV9cIiArIGxhbmddXHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgfSk7XHJcbiAgICAgICAgICBjb250cm9scy5hZGRPcHRpb24obmV3T3B0aW9ucyk7XHJcbiAgICAgICAgfSxcclxuICAgICAgICBlcnJvcjogZnVuY3Rpb24gKGRhdGEpIHtcclxuICAgICAgICAgIGNvbnNvbGUubG9nKGRhdGEpO1xyXG4gICAgICAgIH1cclxuICAgICAgfSk7XHJcbiAgICB9XHJcbiAgfSk7XHJcblxyXG4gIGxldCBjb250cm9scyA9IHNlbGVjdFswXS5zZWxlY3RpemU7XHJcblxyXG4gIGNvbnRyb2xzLm9uKCd0eXBlJywgZGVsYXkoZnVuY3Rpb24gKHZhbCkge1xyXG4gICAgbGV0IHByb2Zlc3Npb25zT2JqID0gcHJvZmVzc2lvbnNFbCA/IHtcInByb2Zlc3Npb25zXCI6IHByb2Zlc3Npb25zRWwudmFsKCl9IDogbnVsbDtcclxuXHJcbiAgICAkLmFqYXgoe1xyXG4gICAgICB0eXBlOiAnUE9TVCcsXHJcbiAgICAgIHVybDogZG9tYWluTmFtZSArIGxhbmcgKyBtZXRob2QsXHJcbiAgICAgIGRhdGE6IHtcclxuICAgICAgICAuLi5wcm9mZXNzaW9uc09iaixcclxuICAgICAgICBcIm5hbWVcIjogdmFsXHJcbiAgICAgIH0sXHJcbiAgICAgIHN1Y2Nlc3M6IGZ1bmN0aW9uIChkYXRhKSB7XHJcbiAgICAgICAgbGV0IG5ld09wdGlvbnMgPSBbXTtcclxuICAgICAgICBkYXRhLmZvckVhY2goZnVuY3Rpb24gKGl0ZW0pIHtcclxuICAgICAgICAgIG5ld09wdGlvbnMucHVzaCh7XHJcbiAgICAgICAgICAgIHZhbHVlOiBpdGVtLmlkLFxyXG4gICAgICAgICAgICB0ZXh0OiBpdGVtW1wibmFtZV9cIiArIGxhbmddXHJcbiAgICAgICAgICB9KTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgY29udHJvbHMuY2xlYXJPcHRpb25zKHRydWUpO1xyXG4gICAgICAgIGxldCBzZWxlY3RlZFZhbHVlcyA9IFtdO1xyXG4gICAgICAgIE9iamVjdC52YWx1ZXMoY29udHJvbHMub3B0aW9ucykuZm9yRWFjaCgoaXRlbSkgPT4ge1xyXG4gICAgICAgICAgc2VsZWN0ZWRWYWx1ZXMucHVzaChpdGVtLnZhbHVlKTtcclxuICAgICAgICB9KTtcclxuICAgICAgICBjb250cm9scy5zZXRWYWx1ZShzZWxlY3RlZFZhbHVlcyk7XHJcbiAgICAgICAgY29udHJvbHMuYWRkT3B0aW9uKG5ld09wdGlvbnMpO1xyXG4gICAgICAgIGNvbnRyb2xzLnNldFRleHRib3hWYWx1ZSh2YWwpO1xyXG4gICAgICAgIGNvbnRyb2xzLmZvY3VzKCk7XHJcbiAgICAgIH0sXHJcbiAgICAgIGVycm9yOiBmdW5jdGlvbiAoZGF0YSkge1xyXG4gICAgICAgIGNvbnNvbGUubG9nKGRhdGEpO1xyXG4gICAgICB9XHJcbiAgICB9KTtcclxuICB9LCAxMDAwKSk7XHJcblxyXG4gIGlmIChwcm9mZXNzaW9uc0VsKSB7XHJcbiAgICBwcm9mZXNzaW9uc0VsLmNoYW5nZShmdW5jdGlvbiAoKSB7XHJcbiAgICAgIGxldCBwcm9mZXNzaW9uc09iaiA9IHtcInByb2Zlc3Npb25zXCI6ICQodGhpcykudmFsKCkgPyAkKHRoaXMpLnZhbCgpIDogW119O1xyXG4gICAgICAkLmFqYXgoe1xyXG4gICAgICAgIHR5cGU6ICdQT1NUJyxcclxuICAgICAgICB1cmw6IGRvbWFpbk5hbWUgKyBsYW5nICsgbWV0aG9kLFxyXG4gICAgICAgIGRhdGE6IHtcclxuICAgICAgICAgIC4uLnByb2Zlc3Npb25zT2JqLFxyXG4gICAgICAgICAgXCJuYW1lXCI6ICcnXHJcbiAgICAgICAgfSxcclxuICAgICAgICBzdWNjZXNzOiBmdW5jdGlvbiAoZGF0YSkge1xyXG4gICAgICAgICAgY29uc29sZS5sb2coJ29rJyk7XHJcbiAgICAgICAgICBsZXQgbmV3T3B0aW9ucyA9IFtdO1xyXG4gICAgICAgICAgZGF0YS5mb3JFYWNoKGZ1bmN0aW9uIChpdGVtKSB7XHJcbiAgICAgICAgICAgIG5ld09wdGlvbnMucHVzaCh7XHJcbiAgICAgICAgICAgICAgdmFsdWU6IGl0ZW0uaWQsXHJcbiAgICAgICAgICAgICAgdGV4dDogaXRlbVtcIm5hbWVfXCIgKyBsYW5nXVxyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgY29udHJvbHMuYWRkT3B0aW9uKG5ld09wdGlvbnMpO1xyXG4gICAgICAgIH0sXHJcbiAgICAgICAgZXJyb3I6IGZ1bmN0aW9uIChkYXRhKSB7XHJcbiAgICAgICAgICBjb25zb2xlLmxvZyhkYXRhKTtcclxuICAgICAgICB9XHJcbiAgICAgIH0pO1xyXG4gICAgfSk7XHJcbiAgfVxyXG59OyovIl0sImZpbGUiOiJhamF4LXNlbGVjdC1vbGQuanMifQ==
