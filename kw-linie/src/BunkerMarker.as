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
		public var latLng: LatLng;
		public static var bunkerTypeColors: Object = {
			"commando 1e lijn": {strokeColor: 0xDDDD88, fillColor: 0x888800},
			"commando 2e lijn": {strokeColor: 0xDDDD88, fillColor: 0x888800},
			"connectiekamer": {strokeColor: 0xDDDD88, fillColor: 0x008888},
			"kanaalbunker": {strokeColor: 0xDDDD88, fillColor: 0x000088},
			"verdediging 1e lijn": {strokeColor: 0xDDDD88, fillColor: 0x880000},
			"verdediging 2e lijn": {strokeColor: 0xDDDD88, fillColor: 0x008800},
			"verdediging antitankcentrum": {strokeColor: 0xDDDD88, fillColor: 0x000000}
		}
		
		public function createMarker(bunker: Object) : Marker
		{
			latLng = new LatLng(bunker.lat, bunker.lng);
			var marker: Marker = new Marker(latLng,
				new MarkerOptions({
						strokeStyle: new StrokeStyle({thickness:1, color: bunkerTypeColors[bunker.type]["strokeColor"]}),
						fillStyle: new FillStyle({color: bunkerTypeColors[bunker.type]["fillColor"]}),
						radius: 4,
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