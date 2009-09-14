package coo
{
	import com.google.maps.LatLng;

	public class CooConv
	{
		
		public var lb72: Lambert72Coo = new Lambert72Coo();
		public var wgs84: Wgs84Coo = new Wgs84Coo();
		public var mol: Molodensky = new Molodensky();
		
		
		public function lambert2latlng(x: Number, y: Number) : LatLng {
			var latLng: LatLng = lb72.convert(x, y);
			var xyz: Object = lb72.llh2xyz(latLng.lat(), latLng.lng(), 0);
			var xyz2: Object = mol.convert(xyz.x, xyz.y, xyz.z);
			var llh84: Object = wgs84.xyz2llh(xyz2.x, xyz2.y, xyz2.z);
			return new LatLng(llh84.lat, llh84.lng);
		}
	}
}