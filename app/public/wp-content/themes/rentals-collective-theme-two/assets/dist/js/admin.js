(()=>{function t(t,n){var i="undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(!i){if(Array.isArray(t)||(i=function(t,n){if(!t)return;if("string"==typeof t)return e(t,n);var i=Object.prototype.toString.call(t).slice(8,-1);"Object"===i&&t.constructor&&(i=t.constructor.name);if("Map"===i||"Set"===i)return Array.from(t);if("Arguments"===i||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(i))return e(t,n)}(t))||n&&t&&"number"==typeof t.length){i&&(t=i);var a=0,r=function(){};return{s:r,n:function(){return a>=t.length?{done:!0}:{done:!1,value:t[a++]}},e:function(t){throw t},f:r}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var u,o=!0,l=!1;return{s:function(){i=i.call(t)},n:function(){var t=i.next();return o=t.done,t},e:function(t){l=!0,u=t},f:function(){try{o||null==i.return||i.return()}finally{if(l)throw u}}}}function e(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,i=new Array(e);n<e;n++)i[n]=t[n];return i}jQuery((function(t){t("body").on("click",".wc_multi_upload_image_button",(function(e){e.preventDefault();var n=t(this),i=wp.media({title:"Insert image",button:{text:"Use this image"},multiple:!0}).on("select",(function(){var e="",a=i.state().get("selection"),r=new Array,u=0;if(a.each((function(i){r[u]=i.id,e+=","+i.id,"image"===i.attributes.type?t(n).siblings("ul").append('<li data-attachment-id="'+i.id+'"><a href="'+i.attributes.url+'" target="_blank"><img class="true_pre_image" src="'+i.attributes.url+'" /></a><i class=" dashicons dashicons-no delete-img"></i></li>'):t(n).siblings("ul").append('<li data-attachment-id="'+i.id+'"><a href="'+i.attributes.url+'" target="_blank"><img class="true_pre_image" src="'+i.attributes.icon+'" /></a><i class=" dashicons dashicons-no delete-img"></i></li>'),u++})),o=t(n).siblings(".attachments-ids").attr("value")){var o=o+e;t(n).siblings(".attachments-ids").attr("value",o)}else t(n).siblings(".attachments-ids").attr("value",r);t(n).siblings(".wc_multi_remove_image_button").show()})).open()})),t("body").on("click",".wc_multi_remove_image_button",(function(){return t(this).hide().prev().val("").prev().addClass("button").html("Add Media"),t(this).parent().find("ul").empty(),!1}))})),jQuery(document).ready((function(){jQuery(document).on("click",".multi-upload-medias ul li i.delete-img",(function(){var t=[];jQuery(this);jQuery(this).parent().remove(),jQuery(".multi-upload-medias ul li").each((function(){t.push(jQuery(this).attr("data-attachment-id"))})),jQuery(".multi-upload-medias").find('input[type="hidden"]').attr("value",t)}))})),jQuery(document).ready((function(){jQuery(".multi-upload-medias ul").sortable({stop:function(t,e){var n=[];jQuery(".multi-upload-medias ul li").each((function(){n.push(jQuery(this).attr("data-attachment-id"))})),jQuery(".multi-upload-medias").find('input[type="hidden"]').attr("value",n)}})})),document.addEventListener("DOMContentLoaded",(function(){var e,n=document.querySelectorAll(".checkbox-sizes"),i=t(n);try{for(i.s();!(e=i.n()).done;){e.value.addEventListener("click",(function(){var e,i=t(n);try{for(i.s();!(e=i.n()).done;){var a=e.value;a!==this&&(a.checked=!1)}}catch(t){i.e(t)}finally{i.f()}}))}}catch(t){i.e(t)}finally{i.f()}}))})();