import React from 'react'

function api() {
    const [data, setData] = useState([]);


    const fetchData = async() => {
        try {
          const response = await fetch("http://127.0.0.1:8000/api/bebelino");
  
          if(!response.ok) {
            throw new Error("Network error");
          }
  
          const data = await response.json();
  
          setData(data);
          console.log(data)
          // console.log(data.data)
          console.log(Array.isArray(data));
        } catch (error) {
          console.error("Problem getting data:",error);
        }
      }

      useEffect(() => {
        fetchData();
      }, []); 

  return (
    <div>api</div>
  )
}

export default api