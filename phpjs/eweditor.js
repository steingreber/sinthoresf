// create editor
function ew_CreateEditor(formid, name, cols, rows, readonly) {
	if (typeof CKEDITOR == "undefined" || name.indexOf("$rowindex$") > -1)
		return;
	var $ = jQuery, form = $("#" + formid)[0], el = ew_GetElement(name, form);
	if (!el)
		return;
	var longname = formid + "$" + name + "$";
	var args = {"id": name, "form": form, "enabled": true};
	$(el).attr("id", longname).trigger("create", [args]);
	if (!args.enabled)
		return;
	var w = (cols ? Math.abs(cols) : 35) * 2 + "em"; // width
	var h = ((rows ? Math.abs(rows) : 4) + 4) * 1.5 + "em"; // height
	var path = window.location.href.substring(0, window.location.href.lastIndexOf("/") + 1);
	var settings = { 

		//width: w, // DO NOT specify width when creating editor
		height: h,
		autoUpdateElement: false,
		baseHref: 'ckeditor/'
	};
	var args = {"id": name, "form": form, "enabled": true, "settings": settings};
	$(el).attr("id", longname).trigger("create", [args]);
	if (!args.enabled)
		return;
	if (readonly) {
		new ew_ReadOnlyTextArea(el, w, h);
	} else {
		var editor = {
			name: name,
			active: false,
			instance: null,
			create: function() {
				this.instance = CKEDITOR.replace(el, args.settings);
				this.active = true;
			},
			set: function() { // update value from textarea to editor
				if (this.instance) this.instance.setData(this.instance.element.value);
			},
			save: function() { // update value from editor to textarea
				if (this.instance) this.instance.updateElement();
				var args = {"id": name, "form": form, "value": ew_RemoveSpaces(el.value)};
				$(el).trigger("save", [args]).val(args.value);
			},
			focus: function() { // focus editor
				if (this.instance) this.instance.focus();
			},
			destroy: function() { // destroy
				if (this.instance) this.instance.destroy();
			}			
		};
		$(el).data("editor", editor).addClass("editor");
	}
}
