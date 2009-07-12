package
{
	
	import com.google.maps.LatLng;
	import com.google.maps.services.ClientGeocoder;
	import com.google.maps.services.ClientGeocoderOptions;
	import com.google.maps.services.GeocodingEvent;
	import com.google.maps.services.GeocodingResponse;
	import com.google.maps.services.Placemark;
	
	import flash.utils.getQualifiedClassName;

	public class Geocode
	{
		
		public var f: Function;
		
		public function Geocode()
		{
		}
		
		public function geocodingSuccessHandler(event: GeocodingEvent) : void {
			var response: GeocodingResponse = event.response;
        	var bestAccuracy: int = 0;
        	var bestAddress: Object = null;
        	for each (var pm : Placemark in response.placemarks) {
        		var address: Object = flatten(pm.addressDetails);
    			var accuracy: int = address['Accuracy'];
    			if (accuracy > bestAccuracy) {
    				bestAddress = address;
    				bestAccuracy = accuracy;
    			} else if (accuracy == bestAccuracy) {
    				trace(bestAddress + " " + address);
    			}
        	}
        	f(bestAddress);
		}
		
		public function findAddress(latLng: LatLng) : void {
			var geocoder:ClientGeocoder;
			var geocoderOptions:ClientGeocoderOptions;
			
			geocoderOptions = new ClientGeocoderOptions({
				language: "nl", 
				countryCode: "BE"});
			geocoder = new ClientGeocoder(geocoderOptions);
			geocoder.addEventListener(
				GeocodingEvent.GEOCODING_SUCCESS,
				geocodingSuccessHandler);
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
		
	}

}
