package
{
	import com.google.maps.InfoWindowOptions;
	import com.google.maps.LatLng;
	import com.google.maps.MapMouseEvent;
	import com.google.maps.overlays.Marker;
	import com.google.maps.overlays.MarkerOptions;
	import com.google.maps.styles.FillStyle;
	import com.google.maps.styles.StrokeStyle;
	
	import mx.controls.Alert;
	import mx.core.Application;
	
	public class BunkerMarker
	{

		public var bunkerThumb: BunkerThumb;
		public var bunker: Object;
		public var marker: Marker;
		public var latLng: LatLng;
		public static var bunkerTypeColors: Object = {
			"commando 1e lijn": {strokeColor: 0xDDDD88, fillColor: 0x888800, closed: false, visible: true},
			"commando 2e lijn": {strokeColor: 0xDDDD88, fillColor: 0x888800, closed: false, visible: true},
			"connectiekamer": {strokeColor: 0xDDDD88, fillColor: 0x008888, closed: false, visible: false},
			"bruggenhoofd mechelen": {strokeColor: 0xDDDD88, fillColor: 0x000088, closed: false, visible: true},
			"verdediging 1e lijn": {strokeColor: 0xDDDD88, fillColor: 0x880000, closed: false, visible: true},
			"verdediging 2e lijn": {strokeColor: 0xDDDD88, fillColor: 0x008800, closed: false, visible: true},
			"verdediging antitankcentrum": {strokeColor: 0xDDDD88, fillColor: 0x000000, closed: true, visible: true}
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
			Application.application.sizer.addChild(bunkerThumb);
            bunkerThumb.validateSize(true);
                
			var w: int = bunkerThumb.measuredWidth;
			bunkerThumb.width = w;

			marker.openInfoWindow(
				new InfoWindowOptions({
						customContent: bunkerThumb,
						width: w,
						drawDefaultFrame:true
					}));
		}

	}
}