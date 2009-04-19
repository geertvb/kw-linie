package
{
	import com.google.maps.LatLng;
	
	import flash.events.Event;
	
	import mx.controls.TextArea;
	import mx.rpc.remoting.mxml.RemoteObject;
	
	public class BunkerUpdater
	{
		public var bunker: Object;
		public var log: TextArea;
		public var latLng: LatLng;
		public var bunkerService: RemoteObject;
		
		public function BunkerUpdater()
		{
		}
		
		public function newAddress(address: Object) : void {
			bunker.gewest = address["AdministrativeAreaName"];
			bunker.provincie = address["SubAdministrativeAreaName"];
			bunker.gemeente = address["LocalityName"];
			bunker.deelgemeente =address["DependentLocalityName"];
			bunker.straat = address["ThoroughfareName"];
			bunker.postcode = address["PostalCodeNumber"];

    		for (var i: String in bunker) {
    			log.text += i +": " + bunker[i] + "\n";
    		}
			bunkerService.save(bunker);
		}
		
		public function update(event: Event) : void {
			var geocode: Geocode = new Geocode();
			geocode.findAddress(latLng, newAddress);
			this.log.text += "bunker " + this.bunker.nummer + " \n";
		}

	}
}