let ajaxSelect=function(a,b=null,c=!0){function d(a){let b=[];a.forEach(function(a){b.push({value:a.id,text:a["name"+(c?"_"+j:"")]})}),m.addOption(b)}function e(){let a=[];Object.values(m.options).forEach(b=>{a.push(b.value)}),m.setValue(a)}function f(a){m.setTextboxValue(a),m.focus()}function g(){m.addOption({$order:0,value:"",text:a.data("default")})}function h(b=null,c="",h=!1){$.ajax({type:"POST",url:k,data:{...b,name:c},success:function(b){m.clearOptions(),e(),a.data("default")&&g(),d(b),h&&f(c)},error:function(a){console.log(a)}})}const i=a.data("method"),j=window.Laravel.lang,k="/"+j+"/"+i;let l=a.selectize({plugins:a[0].hasAttribute("multiple")?["remove_button","silent_remove","stop_backspace_delete"]:null,allowEmptyOption:!0,onInitialize:function(){let a=b?b.val()?{professions:toArray(b.val())}:null:null;h(a)}}),m=l[0].selectize;m.on("type",delay(function(a){let c=b?b.val()?{professions:toArray(b.val())}:null:null;h(c,a,!0)},600)),m.on("change",function(){m.$control_input[0].placeholder=a[0].attributes.placeholder.value}),a[0].hasAttribute("multiple")||m.on("focus",function(){m.clear()}),this.update=function(a){h(a)}};function delay(a,b){let c=0;return function(){let d=this,e=arguments;clearTimeout(c),c=setTimeout(function(){a.apply(d,e)},b||0)}}Selectize.define("silent_remove",function(){let a=this;this.on("item_remove",function(){this.plugin_silent_remove_in_remove=!0}),this.search=function(){let b=a.search;return function(){return"undefined"==typeof this.plugin_silent_remove_in_remove?b.apply(this,arguments):(delete this.plugin_silent_remove_in_remove,{items:{},query:[],tokens:[]})}}()}),Selectize.define("stop_backspace_delete",function(){let a=this;this.deleteSelection=function(){let b=a.deleteSelection;return function(a){return!(a&&8===a.keyCode)&&b.apply(this,arguments)}}()});
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImFqYXgtc2VsZWN0LmpzIl0sIm5hbWVzIjpbImFqYXhTZWxlY3QiLCJlbCIsInBhcmVudEVsIiwibXVsdGlMYW5nIiwicmVuZGVyTmV3T3B0aW9ucyIsImRhdGEiLCJuZXdPcHRpb25zIiwiZm9yRWFjaCIsIml0ZW0iLCJwdXNoIiwidmFsdWUiLCJpZCIsInRleHQiLCJsYW5nIiwiY29udHJvbHMiLCJhZGRPcHRpb24iLCJzYXZlT2xkT3B0aW9ucyIsInNlbGVjdGVkVmFsdWVzIiwiT2JqZWN0IiwidmFsdWVzIiwib3B0aW9ucyIsInNldFZhbHVlIiwiZm9jdXNPblNlYXJjaEZpZWxkIiwidmFsIiwic2V0VGV4dGJveFZhbHVlIiwiZm9jdXMiLCJhZGREZWZhdWx0T3B0aW9uIiwiJG9yZGVyIiwic2VuZFJlcXVlc3QiLCJhZGRpdGlvbmFsRGF0YSIsInNlYXJjaFZhbHVlIiwiJCIsImFqYXgiLCJ0eXBlIiwidXJsIiwic3VjY2VzcyIsImNsZWFyT3B0aW9ucyIsImVycm9yIiwiY29uc29sZSIsImxvZyIsIm1ldGhvZCIsIndpbmRvdyIsIkxhcmF2ZWwiLCJzZWxlY3QiLCJzZWxlY3RpemUiLCJwbHVnaW5zIiwiaGFzQXR0cmlidXRlIiwiYWxsb3dFbXB0eU9wdGlvbiIsIm9uSW5pdGlhbGl6ZSIsInRvQXJyYXkiLCJvbiIsImRlbGF5IiwiJGNvbnRyb2xfaW5wdXQiLCJwbGFjZWhvbGRlciIsImF0dHJpYnV0ZXMiLCJjbGVhciIsInVwZGF0ZSIsImNhbGxiYWNrIiwibXMiLCJ0aW1lciIsImNvbnRleHQiLCJhcmdzIiwiYXJndW1lbnRzIiwiY2xlYXJUaW1lb3V0Iiwic2V0VGltZW91dCIsImFwcGx5IiwiU2VsZWN0aXplIiwiZGVmaW5lIiwic2VsZiIsInBsdWdpbl9zaWxlbnRfcmVtb3ZlX2luX3JlbW92ZSIsInNlYXJjaCIsIm9yaWdpbmFsIiwiaXRlbXMiLCJxdWVyeSIsInRva2VucyIsImRlbGV0ZVNlbGVjdGlvbiIsImUiLCJrZXlDb2RlIl0sIm1h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LENBQUcsSUFBWCxDQUVBLEtBQUtPLGVBQUwsQ0FBd0IsVUFBVyxDQUNqQyxHQUFJSixDQUFBQSxDQUFRLENBQUdILENBQUksQ0FBQ08sZUFBcEIsQ0FFQSxNQUFPLFVBQVVDLENBQVYsQ0FBYSxTQUNiQSxDQUFELEVBQW9CLENBQWQsR0FBQUEsQ0FBQyxDQUFDQyxPQURNLEdBRVROLENBQVEsQ0FBQ04sS0FBVCxDQUFlLElBQWYsQ0FBcUJILFNBQXJCLENBSVYsQ0FDRixDQVZzQixFQVd4QixDQWRELEMiLCJzb3VyY2VzQ29udGVudCI6WyIvL0FqYXggc2VsZWN0IGNvbnN0cnVjdG9yXHJcbmxldCBhamF4U2VsZWN0ID0gZnVuY3Rpb24gKGVsLCBwYXJlbnRFbCA9IG51bGwsIG11bHRpTGFuZyA9IHRydWUpIHtcclxuICBjb25zdCBkb21haW5OYW1lID0gJ2h0dHBzOi8vZGV2My5wYW5hbWEua3ovJyxcclxuICAgIG1ldGhvZCA9IGVsLmRhdGEoJ21ldGhvZCcpLFxyXG4gICAgbGFuZyA9IHdpbmRvdy5MYXJhdmVsLmxhbmcsXHJcbiAgICB1cmwgPSBkb21haW5OYW1lICsgbGFuZyArICcvJyArIG1ldGhvZDtcclxuXHJcbiAgLy9pbml0IFNlbGVjdGl6ZVxyXG4gIGxldCBzZWxlY3QgPSBlbC5zZWxlY3RpemUoe1xyXG4gICAgcGx1Z2luczogZWxbMF0uaGFzQXR0cmlidXRlKCdtdWx0aXBsZScpID8gWydyZW1vdmVfYnV0dG9uJywgJ3NpbGVudF9yZW1vdmUnLCAnc3RvcF9iYWNrc3BhY2VfZGVsZXRlJ10gOiBudWxsLFxyXG4gICAgYWxsb3dFbXB0eU9wdGlvbjogdHJ1ZSxcclxuICAgIC8vIHNvcnRGaWVsZDogW1xyXG4gICAgLy8gICB7XHJcbiAgICAvLyAgICAgZmllbGQ6ICd0ZXh0JyxcclxuICAgIC8vICAgICBkaXJlY3Rpb246ICdhc2MnXHJcbiAgICAvLyAgIH1cclxuICAgIC8vIF0sXHJcbiAgICBvbkluaXRpYWxpemU6IGZ1bmN0aW9uICgpIHtcclxuICAgICAgbGV0IGFkZGl0aW9uYWxEYXRhID0gcGFyZW50RWwgPyAocGFyZW50RWwudmFsKCkgPyB7J3Byb2Zlc3Npb25zJzogdG9BcnJheShwYXJlbnRFbC52YWwoKSl9IDogbnVsbCkgOiBudWxsO1xyXG4gICAgICBzZW5kUmVxdWVzdChhZGRpdGlvbmFsRGF0YSk7XHJcbiAgICB9XHJcbiAgfSk7XHJcbiAgLy9mZXRjaCB0aGUgaW5zdGFuY2VcclxuICBsZXQgY29udHJvbHMgPSBzZWxlY3RbMF0uc2VsZWN0aXplO1xyXG5cclxuICAvL09uIHR5cGUgZXZlbnRcclxuICBjb250cm9scy5vbigndHlwZScsIGRlbGF5KGZ1bmN0aW9uICh2YWwpIHtcclxuICAgIGxldCBhZGRpdGlvbmFsRGF0YSA9IHBhcmVudEVsID8gKHBhcmVudEVsLnZhbCgpID8geydwcm9mZXNzaW9ucyc6IHRvQXJyYXkocGFyZW50RWwudmFsKCkpfSA6IG51bGwpIDogbnVsbDtcclxuICAgIHNlbmRSZXF1ZXN0KGFkZGl0aW9uYWxEYXRhLCB2YWwsIHRydWUpO1xyXG4gIH0sIDYwMCkpO1xyXG5cclxuICBjb250cm9scy5vbignY2hhbmdlJywgZnVuY3Rpb24gKCkge1xyXG4gICAgY29udHJvbHMuJGNvbnRyb2xfaW5wdXRbMF0ucGxhY2Vob2xkZXIgPSBlbFswXS5hdHRyaWJ1dGVzLnBsYWNlaG9sZGVyLnZhbHVlXHJcbiAgfSk7XHJcblxyXG4gIGlmICghZWxbMF0uaGFzQXR0cmlidXRlKCdtdWx0aXBsZScpKSB7XHJcbiAgICBjb250cm9scy5vbignZm9jdXMnLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgIGNvbnRyb2xzLmNsZWFyKCk7XHJcbiAgICB9KTtcclxuICB9XHJcblxyXG4gIGZ1bmN0aW9uIHJlbmRlck5ld09wdGlvbnMoZGF0YSkge1xyXG4gICAgbGV0IG5ld09wdGlvbnMgPSBbXTtcclxuICAgIGRhdGEuZm9yRWFjaChmdW5jdGlvbiAoaXRlbSkge1xyXG4gICAgICBuZXdPcHRpb25zLnB1c2goe1xyXG4gICAgICAgIHZhbHVlOiBpdGVtLmlkLFxyXG4gICAgICAgIHRleHQ6IGl0ZW1bXCJuYW1lXCIgKyAobXVsdGlMYW5nID8gJ18nICsgbGFuZyA6ICcnKV1cclxuICAgICAgfSk7XHJcbiAgICB9KTtcclxuICAgIGNvbnRyb2xzLmFkZE9wdGlvbihuZXdPcHRpb25zKTtcclxuICB9XHJcblxyXG4gIGZ1bmN0aW9uIHNhdmVPbGRPcHRpb25zKCkge1xyXG4gICAgbGV0IHNlbGVjdGVkVmFsdWVzID0gW107XHJcbiAgICBPYmplY3QudmFsdWVzKGNvbnRyb2xzLm9wdGlvbnMpLmZvckVhY2goKGl0ZW0pID0+IHtcclxuICAgICAgc2VsZWN0ZWRWYWx1ZXMucHVzaChpdGVtLnZhbHVlKTtcclxuICAgIH0pO1xyXG4gICAgY29udHJvbHMuc2V0VmFsdWUoc2VsZWN0ZWRWYWx1ZXMpO1xyXG4gIH1cclxuXHJcbiAgZnVuY3Rpb24gZm9jdXNPblNlYXJjaEZpZWxkKHZhbCkge1xyXG4gICAgY29udHJvbHMuc2V0VGV4dGJveFZhbHVlKHZhbCk7XHJcbiAgICBjb250cm9scy5mb2N1cygpO1xyXG4gIH1cclxuXHJcbiAgZnVuY3Rpb24gYWRkRGVmYXVsdE9wdGlvbigpIHtcclxuICAgIGNvbnRyb2xzLmFkZE9wdGlvbih7XHJcbiAgICAgICRvcmRlcjogMCxcclxuICAgICAgdmFsdWU6ICcnLFxyXG4gICAgICB0ZXh0OiBlbC5kYXRhKCdkZWZhdWx0JylcclxuICAgIH0pO1xyXG4gIH1cclxuXHJcbiAgZnVuY3Rpb24gc2VuZFJlcXVlc3QoYWRkaXRpb25hbERhdGEgPSBudWxsLCBzZWFyY2hWYWx1ZSA9ICcnLCBmb2N1cyA9IGZhbHNlKSB7XHJcbiAgICAkLmFqYXgoe1xyXG4gICAgICB0eXBlOiAnUE9TVCcsXHJcbiAgICAgIHVybDogdXJsLFxyXG4gICAgICBkYXRhOiB7XHJcbiAgICAgICAgLi4uYWRkaXRpb25hbERhdGEsXHJcbiAgICAgICAgXCJuYW1lXCI6IHNlYXJjaFZhbHVlXHJcbiAgICAgIH0sXHJcbiAgICAgIHN1Y2Nlc3M6IGZ1bmN0aW9uIChkYXRhKSB7XHJcbiAgICAgICAgY29udHJvbHMuY2xlYXJPcHRpb25zKCk7XHJcblxyXG4gICAgICAgIHNhdmVPbGRPcHRpb25zKCk7XHJcblxyXG4gICAgICAgIGlmIChlbC5kYXRhKCdkZWZhdWx0JykpIHtcclxuICAgICAgICAgIGFkZERlZmF1bHRPcHRpb24oKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIHJlbmRlck5ld09wdGlvbnMoZGF0YSk7XHJcblxyXG4gICAgICAgIGlmIChmb2N1cykge1xyXG4gICAgICAgICAgZm9jdXNPblNlYXJjaEZpZWxkKHNlYXJjaFZhbHVlKTtcclxuICAgICAgICB9XHJcbiAgICAgIH0sXHJcbiAgICAgIGVycm9yOiBmdW5jdGlvbiAoZGF0YSkge1xyXG4gICAgICAgIGNvbnNvbGUubG9nKGRhdGEpO1xyXG4gICAgICB9XHJcbiAgICB9KTtcclxuICB9XHJcblxyXG4gIHRoaXMudXBkYXRlID0gZnVuY3Rpb24gKGFkZGl0aW9uYWxEYXRhKSB7XHJcbiAgICBzZW5kUmVxdWVzdChhZGRpdGlvbmFsRGF0YSk7XHJcbiAgfTtcclxufTtcclxuXHJcbi8vRGVsYXkgZnVuY3Rpb25cclxuZnVuY3Rpb24gZGVsYXkoY2FsbGJhY2ssIG1zKSB7XHJcbiAgbGV0IHRpbWVyID0gMDtcclxuICByZXR1cm4gZnVuY3Rpb24gKCkge1xyXG4gICAgbGV0IGNvbnRleHQgPSB0aGlzLCBhcmdzID0gYXJndW1lbnRzO1xyXG4gICAgY2xlYXJUaW1lb3V0KHRpbWVyKTtcclxuICAgIHRpbWVyID0gc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XHJcbiAgICAgIGNhbGxiYWNrLmFwcGx5KGNvbnRleHQsIGFyZ3MpO1xyXG4gICAgfSwgbXMgfHwgMCk7XHJcbiAgfTtcclxufVxyXG5cclxuU2VsZWN0aXplLmRlZmluZSgnc2lsZW50X3JlbW92ZScsIGZ1bmN0aW9uKG9wdGlvbnMpe1xyXG4gIGxldCBzZWxmID0gdGhpcztcclxuXHJcbiAgLy8gZGVmYW5nIHRoZSBpbnRlcm5hbCBzZWFyY2ggbWV0aG9kIHdoZW4gcmVtb3ZlIGhhcyBiZWVuIGNsaWNrZWRcclxuICB0aGlzLm9uKCdpdGVtX3JlbW92ZScsIGZ1bmN0aW9uKCl7XHJcbiAgICB0aGlzLnBsdWdpbl9zaWxlbnRfcmVtb3ZlX2luX3JlbW92ZSA9IHRydWU7XHJcbiAgfSk7XHJcblxyXG4gIHRoaXMuc2VhcmNoID0gKGZ1bmN0aW9uKCkge1xyXG4gICAgbGV0IG9yaWdpbmFsID0gc2VsZi5zZWFyY2g7XHJcbiAgICByZXR1cm4gZnVuY3Rpb24oKSB7XHJcbiAgICAgIGlmICh0eXBlb2YodGhpcy5wbHVnaW5fc2lsZW50X3JlbW92ZV9pbl9yZW1vdmUpICE9IFwidW5kZWZpbmVkXCIpIHtcclxuICAgICAgICAvLyByZS1lbmFibGUgbm9ybWFsIHNlYXJjaGluZ1xyXG4gICAgICAgIGRlbGV0ZSB0aGlzLnBsdWdpbl9zaWxlbnRfcmVtb3ZlX2luX3JlbW92ZTtcclxuICAgICAgICByZXR1cm4ge1xyXG4gICAgICAgICAgaXRlbXM6IHt9LFxyXG4gICAgICAgICAgcXVlcnk6IFtdLFxyXG4gICAgICAgICAgdG9rZW5zOiBbXVxyXG4gICAgICAgIH07XHJcbiAgICAgIH1cclxuICAgICAgZWxzZSB7XHJcbiAgICAgICAgcmV0dXJuIG9yaWdpbmFsLmFwcGx5KHRoaXMsIGFyZ3VtZW50cyk7XHJcbiAgICAgIH1cclxuICAgIH07XHJcbiAgfSkoKTtcclxufSk7XHJcblxyXG5TZWxlY3RpemUuZGVmaW5lKFwic3RvcF9iYWNrc3BhY2VfZGVsZXRlXCIsIGZ1bmN0aW9uIChvcHRpb25zKSB7XHJcbiAgbGV0IHNlbGYgPSB0aGlzO1xyXG5cclxuICB0aGlzLmRlbGV0ZVNlbGVjdGlvbiA9IChmdW5jdGlvbigpIHtcclxuICAgIGxldCBvcmlnaW5hbCA9IHNlbGYuZGVsZXRlU2VsZWN0aW9uO1xyXG5cclxuICAgIHJldHVybiBmdW5jdGlvbiAoZSkge1xyXG4gICAgICBpZiAoIWUgfHwgZS5rZXlDb2RlICE9PSA4KSB7XHJcbiAgICAgICAgcmV0dXJuIG9yaWdpbmFsLmFwcGx5KHRoaXMsIGFyZ3VtZW50cyk7XHJcbiAgICAgIH1cclxuXHJcbiAgICAgIHJldHVybiBmYWxzZTtcclxuICAgIH07XHJcbiAgfSkoKTtcclxufSk7Il0sImZpbGUiOiJhamF4LXNlbGVjdC5qcyJ9
