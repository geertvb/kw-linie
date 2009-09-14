package coo
{
	import com.google.maps.LatLng;
	
	import flash.geom.Point;

	public class Wgs84Coo
	{
		public var a: Number = 6378137.0;
		public var f: Number = 1.0 / 298.257222101;
		
		public var phi1: Number = 49.0 + (50.0 / 60.0) * Math.PI / 180.0;
		public var phi2: Number = 51.0 + (10.0 / 60.0) * Math.PI / 180.0;
		public var phi0: Number = 50.0 + (47.0 / 60.0) + (52.134 / 3600.0) * Math.PI / 180.0;
		public var lambda0: Number = 4.0 + (21.0 / 60.0) + (33.177 / 3600.0) * Math.PI / 180.0;
		public var x0: Number = 649328.0;
		public var y0: Number = 665262.0;
		
		public var e: Number; 
		public var esq: Number; 
		public var m1: Number;
		public var m2: Number;
		public var t0: Number;
		public var t1: Number;
		public var t2: Number;
		public var n: Number;
		public var g: Number;
		public var r0: Number;
		
		public function ln(value:Number) : Number {
			return Math.log(value);
		}
		
		public function Wgs84Coo() {
			this.esq = 2 * this.f - f*f;
			this.e = Math.sqrt(this.esq);
			this.m1 = Math.cos(this.phi1) / Math.sqrt(1 - Math.pow(this.e,2) * Math.pow(Math.sin(this.phi1),2));
			this.m2 = Math.cos(this.phi2) / Math.sqrt(1 - Math.pow(this.e,2) * Math.pow(Math.sin(this.phi2),2));
			this.t0 = Math.tan(Math.PI/4 - this.phi0/2) / Math.pow((1 - this.e * Math.sin(this.phi0))/(1 + this.e * Math.sin(this.phi0)),this.e/2);
			this.t1 = Math.tan(Math.PI/4 - this.phi1/2) / Math.pow((1 - this.e * Math.sin(this.phi1))/(1 + this.e * Math.sin(this.phi1)),this.e/2);
			this.t2 = Math.tan(Math.PI/4 - this.phi2/2) / Math.pow((1 - this.e * Math.sin(this.phi2))/(1 + this.e * Math.sin(this.phi2)),this.e/2);
			this.n = (this.ln(this.m1)-this.ln(this.m2)) / (this.ln(this.t1) - this.ln(this.t2));
			this.g = this.m1 / (this.n * Math.pow(this.t1, this.n));
			this.r0 = this.a * this.g * Math.pow(this.t0, this.n);			
		}
		
		public function convert(x:Number, y:Number) : LatLng {
			var r: Number = Math.sqrt(Math.pow(x - this.x0, 2) + Math.pow(this.r0 - (y - this.y0),2));
			var t: Number = Math.pow(r / (this.a * this.g), 1 / this.n);
			var theta: Number = Math.atan((x - x0) / (r0 - (y - y0)));
			var lambda: Number = (theta / this.n) + this.lambda0;
			var currentPhi: Number = Math.PI/2 - 2 * Math.atan(t);
			for (var i:Number=0; i<10; i++) {
				var previousPhi: Number = currentPhi;
				currentPhi = Math.PI/2 - 2 * Math.atan(t * Math.pow((1 - this.e * Math.sin(previousPhi)) / (1 + this.e * Math.sin(previousPhi)), this.e/2));
			}
			return new LatLng(currentPhi * 180 / Math.PI, lambda * 180 / Math.PI);
		}
		
		public function invConvert(latLng: LatLng) : Point {
			var phi: Number = latLng.lat() / 180.0 * Math.PI;
			var lambda: Number = latLng.lng() / 180.0 * Math.PI;
			
			var t: Number = Math.tan(Math.PI / 4 - phi / 2) / Math.pow((1 - e * Math.sin(phi)) / (1 + e * Math.sin(phi)), e / 2);
			var r: Number = a * g * Math.pow(t, n);
			var theta: Number = n * (lambda - lambda0);
			var x: Number = x0 + r * Math.sin(theta);
			var y: Number = y0 + r0 - r * Math.cos(theta);
			return new Point(x, y);
		}
		
		public function llh2xyz(lat: Number, lng: Number, height: Number) : Object {
			var slat: Number = Math.sin(lat / 180.0 * Math.PI);
			var clat: Number = Math.cos(lat / 180.0 * Math.PI);
			var slng: Number = Math.sin(lng / 180.0 * Math.PI);
			var clng: Number = Math.cos(lng / 180.0 * Math.PI);
			
			var N: Number = a / Math.pow(1 - esq * slat * slat, 0.5);
			var x: Number = (N + height) * clat * clng;
			var y: Number = (N + height) * clat * slng;
			var z: Number = (N * (1 - esq) + height ) * slat;
			return {x: x, y: y, z: z};
		}
		
		public function xyz2llh(x: Number, y: Number, z: Number) : Object {
			var p2: Number = x*x + y*y;
			var p: Number = Math.pow(p2, 0.5);
			var r: Number = Math.pow(p2 + z*z, 0.5);
			var u: Number = Math.atan( (z / p) * ((1-f) + (e*e * a / r)) );
			var lambda: Number = Math.atan(y / x);
			var phi: Number = Math.atan( ( z * (1-f) + e*e * a * Math.pow(Math.sin(u), 3) ) / ((1-f) * (p - e*e * a * Math.pow(Math.cos(u),3))) );
			var h: Number = p * Math.cos(phi) + z * Math.sin(phi) - a * Math.pow( (1 - e*e * Math.pow(Math.sin(phi),2)), 0.5);
			return {lat: phi / Math.PI * 180, lon: lambda / Math.PI * 180, h: h};
		}
		
	}
}