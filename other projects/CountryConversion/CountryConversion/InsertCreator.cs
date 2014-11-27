using System;
using System.Collections.Generic;
using System.IO;
using System.Text;

namespace CountryConversion
{
    class InsertCreator
    {
        private readonly List<String> countryList;
        private readonly string sqlFile;

        public InsertCreator(String file)
        {
            sqlFile = file;
            countryList = new List<string>();
        }

        public void AddCountry(string country)
        {
            var sb = new StringBuilder();
            sb.Append("INSERT INTO Landen (land) VALUES ('");
            sb.Append(country);
            sb.Append("');");
            countryList.Add(sb.ToString());
            Console.WriteLine(sb.ToString());
        }

        public void FinalizeFile()
        {
            File.WriteAllLines(sqlFile, countryList);
        }
    }
}
