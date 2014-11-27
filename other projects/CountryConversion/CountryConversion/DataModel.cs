using System.Collections.Generic;

namespace CountryConversion
{
    public class Country
    {
        public string countryName { get; set; }
    }

    public class Countries
    {
        public List<Country> country { get; set; }
    }

    public class RootObject
    {
        public Countries countries { get; set; }
    }
}
