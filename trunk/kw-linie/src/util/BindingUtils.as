package util
{
	import components.Aanwezig;
	import components.Bekeken;
	import components.ComboBoxEx;
	
	import mx.controls.CheckBox;
	import mx.controls.ComboBox;
	import mx.controls.DateField;
	import mx.controls.NumericStepper;
	import mx.controls.TextArea;
	import mx.controls.TextInput;
	import mx.core.UIComponent;
	

	public class BindingUtils {
		
		public static function isEnabled(component: UIComponent) : Boolean {
			if (component.enabled && component.parent != null && component.parent is UIComponent) {
				return isEnabled(UIComponent(component.parent));
			} else {
				return component.enabled;
			}
		}
		
		public static function isEmpty(ti: TextInput) : Boolean {
			return !isEnabled(ti) || (ti.text == null) || (ti.text == "");
		}
		
		public static function isEmptyTextArea(ti: TextArea) : Boolean {
			return !isEnabled(ti) || (ti.text == null) || (ti.text == "");
		}
		
		public static function textinputToString(ti: TextInput) : Object {
			if (isEmpty(ti)) {
				return null;
			} else {
				return ti.text;
			}
		}
		
		public static function textAreaToString(ti: TextArea) : Object {
			if (isEmptyTextArea(ti)) {
				return "";
			} else {
				return ti.text;
			}
		}
		
		public static function textinputToInteger(ti: TextInput) : Object {
			if (isEmpty(ti)) {
				return null;
			} else {
				return int(ti.text);
			}
		}
		
		public static function numericStepperToInteger(ns: NumericStepper) : Object {
			return ns.value;
		}
		
		public static function textinputToFloat(ti: TextInput) : Object {
			if (isEmpty(ti)) {
				return "";
			} else {
				return Number(ti.text);
			}
		}
		
		public static function checkboxToBoolean(cb: CheckBox) : Object {
			if (isEnabled(cb)) {
				return cb.selected;
			} else {
				return null;
			}
		}
		
		public static function comboboxToString(cb: ComboBox) : Object {
			if (isEnabled(cb) && cb.selectedIndex>=0) {
				return cb.selectedLabel;
			} else {
				return null;
			}
		}
		
		public static function comboBoxExToString(cb: ComboBoxEx) : Object {
			if (isEnabled(cb) && cb.selectedIndex>=0) {
				return cb.selectedValue;
			} else {
				return null;
			}
		}
		
		public static function dateFieldToDate(df: DateField) : Object {
			if (isEnabled(df)) {
				return DateField.dateToString(df.selectedDate, "YYYY-MM-DD");
			} else {
				return null;
			}
		}
		
		public static function bekekenToString(bekeken: Bekeken) : Object {
			if (isEnabled(bekeken)) {
				return bekeken.value;
			} else {
				return null;
			}
		}
		
		public static function aanwezigToString(aanwezig: Aanwezig) : Object {
			if (isEnabled(aanwezig)) {
				return aanwezig.value;
			} else {
				return null;
			}
		}
		
	}

}