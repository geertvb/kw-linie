package util
{
	import mx.controls.CheckBox;
	import mx.controls.ComboBox;
	import mx.controls.DateField;
	import mx.controls.NumericStepper;
	import mx.controls.TextArea;
	import mx.controls.TextInput;
	

	public class BindingUtils {
		
		public static function isEmpty(ti: TextInput) : Boolean {
			return (ti.text == null) || (ti.text == "");
		}
		
		public static function isEmptyTextArea(ti: TextArea) : Boolean {
			return (ti.text == null) || (ti.text == "");
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
			if (cb.enabled) {
				return cb.selected;
			} else {
				return null;
			}
		}
		
		public static function comboboxToString(cb: ComboBox) : Object {
			if (cb.enabled && cb.selectedIndex>=0) {
				return cb.selectedLabel;
			} else {
				return null;
			}
		}
		
		public static function dateFieldToDate(df: DateField) : Object {
			if (df.enabled) {
				return df.selectedDate;
			} else {
				return null;
			}
		}
	
	}

}