package
{
	
	import com.google.maps.LatLng;
	import com.google.maps.services.ClientGeocoder;
	import com.google.maps.services.ClientGeocoderOptions;
	import com.google.maps.services.GeocodingEvent;
	import com.google.maps.services.GeocodingResponse;
	import com.google.maps.services.Placemark;
	
	import coo.Lambert72Coo;
	
	import flash.utils.Timer;
	import flash.utils.getQualifiedClassName;

	public class Geocode
	{
		
		public function Geocode()
		{
		}
		
		public function findAddress(latLng: LatLng, f: Function) : void {
			var geocoder:ClientGeocoder;
			var geocoderOptions:ClientGeocoderOptions;
			
			geocoderOptions = new ClientGeocoderOptions({language: "nl", countryCode: "BE"});
			geocoder = new ClientGeocoder(geocoderOptions);
			geocoder.addEventListener(
				GeocodingEvent.GEOCODING_SUCCESS,
				function (event: GeocodingEvent) : void {
		        	var response: GeocodingResponse = event.response;
		        	var bestAccuracy: int = 0;
		        	var bestAddress: Object = null;
		        	for each (var pm : Placemark in response.placemarks) {
		        		var address: Object = flatten(pm.addressDetails);
		    			var accuracy: int = address['Accuracy'];
		    			if (accuracy > bestAccuracy) {
		    				bestAddress = address;
		    				bestAccuracy = accuracy;
		    			}
		        	}
		        	f(bestAddress);
		        });
			geocoder.reverseGeocode(latLng);
		
		}
		
		public function isObject(value: Object) : Boolean {
			return value != null && getQualifiedClassName(value) == "Object";
		}
		
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
		
		/*
        public function getAllGeocodeInfo() : void {
        	var c: Lambert72Coo = new Lambert72Coo();
        	var bunkers:Array = bunkerResponder.lastResult as Array;
        	var asdf: int = 0;
        	for each (var bunker: Object in bunkers) {
        		if (bunker.x != null && bunker.y != null) {
        			// LatLng
        			var latLng: LatLng;
        			if (bunker.lat == null || bunker.lng == null) {
        				latLng = c.convert(bunker.x, bunker.y);
        				bunker.lat = latLng.lat();
        				bunker.lng = latLng.lng();
        			} else {
        				latLng = new LatLng(bunker.lat as Number, bunker.lng as Number);
        			}
            		
            		if (bunker.gemeente == null) {
            			asdf += 1000 + Math.random() * 5000;
			            var myTimer:Timer = new Timer(asdf, 1);
			            var tobj: BunkerUpdater = new BunkerUpdater();
			            tobj.bunkerService = bunkerService;
			            tobj.bunker = bunker;
			            tobj.latLng = latLng;
			            myTimer.addEventListener("timer", tobj.update);
			            myTimer.start();
            		} 
        		}
        	}
        }
        */


	}

}
