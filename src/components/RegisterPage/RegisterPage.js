import React, {useState} from "react";
import {Button, Label, Modal, TextInput} from "flowbite-react";
import axios from "axios";
import { useAuth } from "../../Context.js";

export function RegisterPage() {
    const [isLoading, setIsLoading] = useState(false);
    const [isRegistered, setIsRegistered] = useState(false);
    const [isSubmitted, setIsSubmitted] = useState(false);
    const [openModal,setOpenModal] = useState(true);
    // const  [email, setEmail] = useState("");
    const [formData, setFormData] = useState({first_name:"",last_name:"",phone_number:"",email:"",password:""});
    const { setIsRegisteredTo, setUser } = useAuth();

    const handleChange = (event) => {
      const {name, value} = event.target;
      event.preventDefault();
      setFormData((prevFormData) => ({ ...prevFormData, [name]: value }));
    }

    const handleSubmit = async (event) => {
      event.preventDefault();
      setIsLoading(true);

      try {
        const response = await axios.post("http://127.0.0.1:8000/api/register", formData,{
          method:"POST",
          headers:{
            'Content-Type':'application/json'
          },
          body: JSON.stringify(formData)
        });

        console.log(response)

        if(response.status === 200) {
          setIsSubmitted(true);
          setIsRegistered(true);
          setIsRegisteredTo(true);
          setUser({first_name:formData.first_name, last_name:formData.last_name})
          alert("User has registered successfully")
        }
      } catch (error) {
        alert(`Failed to register user`);
      } finally {
        setIsLoading(false);
        setOpenModal(false);
      }
   }

    function onCloseModal(e) {
        e.preventDefault();
        setOpenModal(false);
        setFormData({email:"", password:""})
    }

    return ( 
      <div>
        {
          isRegistered ? 
          alert("User registered successfully") :
          <Modal show={openModal} size="md" onClose={onCloseModal} popup>
          <Modal.Header />
          <Modal.Body> 
          <form onSubmit={handleSubmit}>
            <div className="space-y-6">
              <h3 className="text-xl font-medium text-gray-900 dark:text-white">Register to Bid </h3>
              <div>
                <div className="mb-2 block">
                  <Label htmlFor="First Name" value="First Name" />
                </div>
                <TextInput
                  id="first_name"
                  name="first_name"
                  placeholder="First Name"
                  type="text"
                  value={formData.first_name}
                  onChange={handleChange}
                  required
                />
              </div>
              <div>
                <div className="mb-2 block">
                  <Label htmlFor="Last Name" value="Last Name" />
                </div>
                <TextInput
                  id="last_name"
                  name="last_name"
                  placeholder="Last Name"
                  value={formData.last_name}
                  onChange={handleChange}
                  required
                />
              </div>
              <div>
                <div className="mb-2 block">
                  <Label htmlFor="Phone Number" value="Phone Number" />
                </div>
                <TextInput
                  id="phone_number"
                  name="phone_number"
                  placeholder="Phone Number"
                  type="number"
                  value={formData.phone_number}
                  onChange={handleChange}
                  required
                />
              </div>
              <div>
                <div className="mb-2 block">
                  <Label htmlFor="email" value="Your email" />
                </div>
                <TextInput
                  id="email"
                  name="email"
                  placeholder="name@company.com"
                  value={formData.email}
                  type="email"
                  onChange={handleChange}
                  required
                />
              </div>
              <div>
                <div className="mb-2 block">
                  <Label htmlFor="password" value="Your password" />
                </div>
                <TextInput 
                  onChange={handleChange} 
                  name="password" 
                  id="password" 
                  type="password" 
                  value={formData.password} 
                  required 
                />
              </div>
              <div className="flex justify-end">
                {
                  isLoading ? 
                  <button disabled type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 inline-flex items-center">
                  <svg aria-hidden="true" role="status" class="inline w-4 h-4 me-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                  <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                  </svg>
                  Loading...
                  </button> :
                   <div>
                      <Button type="submit" color="light">Create Account</Button>
                   </div> 
                }
              </div>
              <div className="flex text-sm font-medium text-gray-500 dark:text-gray-300">
                Already Registered?&nbsp;
                <a href="#" className="text-cyan-700 hover:underline dark:text-cyan-500">
                  Login
                </a>
              </div>
            </div>
          </form>
          </Modal.Body>
        </Modal> 
      }
</div>
);
}


export default RegisterPage