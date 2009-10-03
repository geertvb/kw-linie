package
{
	import flash.display.Bitmap;
	
	import mx.core.UIComponent;
	
	public class BitmapHolder extends UIComponent {
		
		private var _source: Bitmap = null;
		private var _sourceChanged: Boolean = false;
		private var _content: Bitmap;
		
		[Bindable]
		public function set source(value: Bitmap) : void {
			if (value != source) {
				_source = value;
				_sourceChanged = true;
				invalidateProperties();
			}
		}
		
		public function get source() : Bitmap {
			return _source;
		}
		
		override protected function commitProperties() : void {
			if (_sourceChanged) {
				_sourceChanged = false;
				
				if (_content != null) {
					if (contains(_content)) {
						removeChild(_content);
					}
					_content = null;
				}
				
				if (_source != null) {
					_content = _source;
					addChild(_content);
				}
				
				invalidateSize();
				invalidateDisplayList();
			}
		}
		
	}
}