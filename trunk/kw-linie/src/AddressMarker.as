package
{
	import com.google.maps.MapMouseEvent;
	import com.google.maps.overlays.Marker;
	import com.google.maps.overlays.MarkerOptions;
	import com.google.maps.services.Placemark;
	import com.google.maps.styles.FillStyle;
	import com.google.maps.styles.StrokeStyle;
	
	import flash.events.Event;
	import flash.utils.getQualifiedClassName;
	
	public class AddressMarker
	{
		public var address: Object;
		
		public var marker: Marker;
		
		public var callBack: Function;
		
		public function flatten(src: Object, tgt: Object = null) : Object {
			if (tgt == null) {
				tgt = new Object();
			}
			for (var i: String in src) {
				var value: Object = src[i];
				if (isObject(value)) {
					flatten(value, tgt);
				} else {
					tgt[i] = value;
				}
			}
			return tgt;
		}
		
		public function isObject(value: Object) : Boolean {
			return value != null && getQualifiedClassName(value) == "Object";
		}
		
		public function AddressMarker(pm:Placemark) {
    		address = flatten(pm.addressDetails);
			marker = new Marker(pm.point,
				new MarkerOptions({
						strokeStyle: new StrokeStyle({color: 0xDDDD88}),
						fillStyle: new FillStyle({color: 0x000088, alpha: 0.7}),
						radius: 8,
						hasShadow: true
					}));
			marker.addEventListener(MapMouseEvent.CLICK, click);
		}
		
		public function click(event: Event) : void {
			callBack(this.address);
		}

	}
}