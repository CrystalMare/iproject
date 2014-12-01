using System;
using System.IO;
using Newtonsoft.Json;

namespace CountryConversion
{
    class Program
    {
        static void Main(string[] args)
        {
            var file = new InsertCreator("output.sql");
            var json = File.ReadAllText("data.json");
            var data = JsonConvert.DeserializeObject<RootObject>(json);
            foreach (var country in data.countries.country)
            {
                file.AddCountry(country.countryName);
            }
            file.FinalizeFile();
            Console.ReadLine();
        }
    }
}
