package components
{

	import mx.controls.ComboBox;
	import mx.utils.ObjectUtil;
	
	public class ComboBoxEx extends ComboBox
 	{
		private var _selectedValue: Object;
		private var _selectedValueSet: Boolean = false;

		private var _valueField: String = "value";
		private var _valueFieldSet: Boolean = false;
		
        private var _dataProviderSet: Boolean = false;

		public function set valueField(value: String) : void {
            _valueFieldSet = true;
            _valueField = value;
            invalidateProperties();
		}

		public function set selectedValue(value: Object) : void {
            _selectedValueSet = true;
            _selectedValue = value;
            invalidateProperties();
		}

		[Bindable("change")]
		[Bindable("valueCommit")]
		public function get selectedValue() : Object {
			if (this.selectedItem is String) {
				return this.selectedItem;
			} else if (this.selectedItem && this._valueField) {
				return this.selectedItem[this._valueField];
			} else {
				return null;
			}
		}

		override public function set dataProvider(o: Object) : void {
			super.dataProvider = o;
			
			if (o!=null) {
				_dataProviderSet = true;
			}
		}


		override protected function commitProperties(): void {
			super.commitProperties();
			
			if (_selectedValueSet && _dataProviderSet) {
				_selectedValueSet=false;
				for (var i:int=0; i<this.dataProvider.length; i++) {
					var item: Object = this.dataProvider[i];
					
					var v: * = getField(item, _valueField);
					if (v == _selectedValue || ObjectUtil.compare(v, _selectedValue, 1) == 0) {
						this.selectedIndex = i;
						return;
					}
				}
				this.selectedIndex = -1;
			}
		}
		
		public function getField(item: *, fieldname: String) : * {
			if (typeof(item) == "object") {
	            try {
	            	item = item[fieldname];
	            } catch(e:Error) {
	            }
	        }
			return item;
		}

	} 

}
