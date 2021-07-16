let ajaxSelect=function(t,e=null,n=!0,o=null){let l=this;function s(t=null,e="",n=!1,o=!0){$.ajax({type:"GET",url:l.url,data:{...t,name:e},success:function(t){o&&l.controls.clearOptions(!0),0!==t.data.length?(l.saveOldOptions(),l.el.data("default")&&l.addDefaultOption(),l.renderNewOptions(t.data),n&&l.focusOnSearchField(e),l.removeMessage()):(l.controls.$control[0].classList.add("no-results"),l.addMessage()),l.pageCounter=1,l.lastPage=t.current_page===t.last_page},error:function(t){console.log(t)}})}this.el=t,this.parentEl=e,e&&(this.parentElName=e.attr("name")),this.multiLang=n,this.method=this.el.data("method"),this.domainName=document.location.origin,this.lang=window.Laravel.lang,this.url=this.domainName+"/"+this.lang+"/"+this.method,this.pageCounter=1,this.lastPage=!1,this.loadEnable=!0,this.select=this.el.selectize({plugins:this.el[0].hasAttribute("multiple")?["remove_button","silent_remove","stop_backspace_delete"]:null,allowEmptyOption:!0,maxOptions:1e4,maxItems:o||(t.attr("multiple")?null:1),onInitialize:function(){let t;l.parentEl&&l.parentEl.val()&&((t={})[l.parentElName]=toArray(l.parentEl.val())),s(t)},onChange:function(){this.$input[0].dispatchEvent(changeEvent)}}),this.controls=this.select[0].selectize,this.dropdownContent=this.controls.$dropdown_content,this.noResultsDefaultMsg={en:"No results",ru:"Ничего не найдено",kk:"Ештене табылган жок"},this.noResultsMsg=this.controls.$input[0].dataset.noresults?this.controls.$input[0].dataset.noresults:this.noResultsDefaultMsg[this.lang],this.controls.on("type",delay(function(t){let e;l.parentEl&&l.parentEl.val()&&((e={})[l.parentElName]=toArray(l.parentEl.val())),s(e,t,!0)},1e3)),this.controls.on("change",function(t){l.controls.$control_input[0].placeholder=l.el[0].attributes.placeholder.value,l.loadEnable=!1,setTimeout(function(){l.loadEnable=!0},150)}),this.controls.on("blur",function(){l.controls.$control[0].classList.remove("no-results"),l.removeMessage()}),this.controls.on("focus",function(){l.el[0].hasAttribute("multiple")||l.controls.clear()}),this.dropdownContent.on("scroll",function(){let t;if(l.parentEl&&l.parentEl.val()&&((t={})[l.parentElName]=toArray(l.parentEl.val())),l.dropdownContent[0].scrollHeight-l.dropdownContent[0].scrollTop-l.dropdownContent[0].clientHeight<1e3&&l.loadEnable&&!l.lastPage){l.loadEnable=!1,l.controls.$dropdown[0].classList.add("loading");let e=l.dropdownContent[0].scrollTop;l.getNextPage(t,e)}}),this.renderNewOptions=function(t){let e=[];t.forEach(function(t){e.push({value:t.id,text:t["name"+(l.multiLang?"_"+l.lang:"")]})}),l.controls.addOption(e)},this.saveOldOptions=function(){let t=[];Object.values(l.controls.options).forEach(e=>{t.push(e.value)}),l.controls.setValue(t,!0)},this.addMessage=function(){let t=document.createElement("div");t.className="noresults-message",t.innerHTML=l.noResultsMsg,l.controls.$control[0].parentElement.append(t)},this.removeMessage=function(){let t=l.controls.$control[0].parentElement.querySelector(".noresults-message");t&&t.remove()},this.focusOnSearchField=function(t){l.controls.setTextboxValue(t),l.controls.focus()},this.addDefaultOption=function(){l.controls.addOption({$order:0,value:"",text:l.el.data("default")})},this.getNextPage=function(t,e){$.ajax({type:"GET",url:l.url,data:{...t,name:l.controls.lastQuery,page:l.pageCounter+1},success:function(t){l.renderNewOptions(t.data),l.controls.refreshOptions(),l.loadEnable=!0,l.pageCounter++,setTimeout(function(){l.controls.$dropdown[0].classList.remove("loading")},150),l.dropdownContent[0].scrollTop=e,t.current_page===t.last_page&&(l.lastPage=!0)},error:function(t){console.log(t)}})},this.update=function(t){s(t)},this.clear=function(){l.controls.clear(!0)},this.clearOptions=function(){this.controls.clearOptions(!0)}},ajaxSelect2=function(t,e=!0,n=null,o=null){let l=this;function s(t,e="",n=!1,o=!0){-1!==t&&$.ajax({type:"GET",url:l.url+"/"+t,data:{name:e},success:function(t){o&&l.controls.clearOptions(!0),0!==t.data.length?(l.saveOldOptions(),l.el.data("default")&&l.addDefaultOption(),l.renderNewOptions(t.data),n&&l.focusOnSearchField(e),l.removeMessage()):(l.controls.$control[0].classList.add("no-results"),l.addMessage()),l.pageCounter=1,l.lastPage=t.current_page===t.last_page},error:function(t){console.log(t)}})}this.el=t,this.multiLang=e,this.skillId=n,this.method=this.el.data("method"),this.domainName=document.location.origin,this.lang=window.Laravel.lang,this.url=this.domainName+"/"+this.lang+"/"+this.method,this.pageCounter=1,this.lastPage=!1,this.loadEnable=!0,this.select=this.el.selectize({plugins:this.el[0].hasAttribute("multiple")?["remove_button","silent_remove","stop_backspace_delete"]:null,allowEmptyOption:!0,maxOptions:1e4,maxItems:o,onInitialize:function(){s(l.skillId)},onChange:function(){this.$input[0].dispatchEvent(changeEvent)}}),this.controls=this.select[0].selectize,this.dropdownContent=this.controls.$dropdown_content,this.noResultsDefaultMsg={en:"No results",ru:"Ничего не найдено",kk:"Ештене табылган жок"},this.noResultsMsg=this.controls.$input[0].dataset.noresults?this.controls.$input[0].dataset.noresults:this.noResultsDefaultMsg[this.lang],this.controls.on("type",delay(function(t){s(l.skillId,t,!0)},1e3)),this.controls.on("change",function(){l.controls.$control_input[0].placeholder=l.el[0].attributes.placeholder.value,l.loadEnable=!1,setTimeout(function(){l.loadEnable=!0},150)}),this.controls.on("blur",function(){l.controls.$control[0].classList.remove("no-results"),l.removeMessage()}),this.controls.on("focus",function(){l.el[0].hasAttribute("multiple")||l.controls.clear()}),this.dropdownContent.on("scroll",function(){l.parentEl&&l.parentEl.val()&&toArray(l.parentEl.val());if(l.dropdownContent[0].scrollHeight-l.dropdownContent[0].scrollTop-l.dropdownContent[0].clientHeight<1e3&&l.loadEnable&&!l.lastPage){l.loadEnable=!1,l.controls.$dropdown[0].classList.add("loading");let t=l.dropdownContent[0].scrollTop;l.getNextPage(l.skillId,t)}}),this.renderNewOptions=function(t){let e=[];t.forEach(function(t){e.push({value:t.id,text:t["name"+(l.multiLang?"_"+l.lang:"")]})}),l.controls.addOption(e)},this.saveOldOptions=function(){let t=[];Object.values(l.controls.options).forEach(e=>{t.push(e.value)}),l.controls.setValue(t)},this.addMessage=function(){let t=document.createElement("div");t.className="noresults-message",t.innerHTML=l.noResultsMsg,l.controls.$control[0].parentElement.append(t),setTimeout(function(){l.removeMessage()},3e3)},this.removeMessage=function(){let t=l.controls.$control[0].parentElement.querySelector(".noresults-message");t&&t.remove()},this.focusOnSearchField=function(t){l.controls.setTextboxValue(t),l.controls.focus()},this.addDefaultOption=function(){l.controls.addOption({$order:0,value:"",text:l.el.data("default")})},this.getNextPage=function(t,e){$.ajax({type:"GET",url:l.url+"/"+t,data:{name:l.controls.lastQuery,page:l.pageCounter+1},success:function(t){l.renderNewOptions(t.data),l.controls.refreshOptions(),l.loadEnable=!0,l.pageCounter++,setTimeout(function(){l.controls.$dropdown[0].classList.remove("loading")},150),l.dropdownContent[0].scrollTop=e,t.current_page===t.last_page&&(l.lastPage=!0)},error:function(t){console.log(t)}})},this.update=function(t){s(t)},this.clear=function(){this.controls.clear(!0)},this.clearOptions=function(){this.controls.clearOptions(!0)}};function delay(t,e){let n=0;return function(){let o=this,l=arguments;clearTimeout(n),n=setTimeout(function(){t.apply(o,l)},e||0)}}Selectize.define("silent_remove",function(t){let e=this;this.on("item_remove",function(){this.plugin_silent_remove_in_remove=!0}),this.search=function(){let t=e.search;return function(){return void 0!==this.plugin_silent_remove_in_remove?(delete this.plugin_silent_remove_in_remove,{items:{},query:[],tokens:[]}):t.apply(this,arguments)}}()}),Selectize.define("stop_backspace_delete",function(t){let e=this;this.deleteSelection=function(){let t=e.deleteSelection;return function(e){return(!e||8!==e.keyCode)&&t.apply(this,arguments)}}()});
