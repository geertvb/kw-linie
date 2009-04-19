package
{
	import com.google.maps.InfoWindowOptions;
	import com.google.maps.LatLng;
	import com.google.maps.MapMouseEvent;
	import com.google.maps.overlays.Marker;
	import com.google.maps.overlays.MarkerOptions;
	import com.google.maps.styles.FillStyle;
	import com.google.maps.styles.StrokeStyle;
	
	public class BunkerMarker
	{
		public var bunkerThumb: BunkerThumb;
		public var bunker: Object;
		public var marker: Marker;
		
		public function createMarker(bunker: Object) : Marker
		{
			var latLng: LatLng = new LatLng(bunker.lat, bunker.lng);
			var marker: Marker = new Marker(latLng,
				new MarkerOptions({
						strokeStyle: new StrokeStyle({color: 0xDDDD88}),
						fillStyle: new FillStyle({color: 0x880000}),
						radius: 8,
						hasShadow: true
					}));
			return marker;
		}
		
		public function BunkerMarker(bunker: Object)
		{
			this.bunker = bunker;
			this.marker = createMarker(bunker);
			this.marker.addEventListener(MapMouseEvent.CLICK, click);
			this.bunkerThumb = new BunkerThumb();
		}

		public function click(event:MapMouseEvent) : void {
			bunkerThumb.bunker = bunker;
			marker.openInfoWindow(
				new InfoWindowOptions({
						customContent: bunkerThumb,
						width: bunkerThumb.width,
						drawDefaultFrame:true
					}));
		}
	}
}