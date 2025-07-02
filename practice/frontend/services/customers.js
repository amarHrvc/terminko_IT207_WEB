var CustomersService = {
  get: function () {
    console.log("GEEEEET");
  },

  getCustomers: function () {
    RestClient.get(
      "/customers",
      (customers) => {
        // console.log("CUSTOOOMEEEERSSSSS", customers);

        let select = document.getElementById("customers-list");
        select.innerHTML = "";

        let html = "<option selected>Please select one customer</option>";
        customers.forEach((cust) => {
          //  <option value="1">Becir Isakovic</option>
          // console.log(cust);
          html += `
         <option value="${cust.id}">${cust.last_name} ${cust.first_name}</option>
      `;
        });

        select.innerHTML = html;
      },
      (err) => {
        console.log("ERRRORRRRRRR", err);
      }
    );
  },

  getMeals: function (customer_id) {
    RestClient.get(
      `/customer/meals/${customer_id}`,
      (meals) => {
        console.log("MEALS", meals);

        let tableRows = `<thead>
        <tr>
          <th>Food name</th>
          <th>Food brand</th>
          <th>Meal date</th>
        </tr>
      </thead>
      <tbody>`

        let table = document.getElementById("customer-meals");
        table.innerHTML = "";

        
        meals.forEach((meal) => {
          console.log(meal);
          tableRows += `
          <tr>
          <td>${meal.food_name}</td>
          <td>${meal.food_brand}</td>
          
          <td>${meal.meal_date.split(" ")[0]}</td>
        </tr>`;
        });

        tableRows += `</tbody>`;
        table.innerHTML = tableRows;


      },
      (err) => {
        console.log("ERRRORRRRRRR", err);
      }
    );
  },

  addCustomer: function (firstName, lastName, date) {
    event.preventDefault();

   
    RestClient.post(
      "/customers/add",
      {first_name: firstName, last_name: lastName, birth_date: date},
      (response) => {
        console.log("Customer added successfully", response);
        // Refresh the customers list
        this.getCustomers();
        // Close the modal
        $('#add-customer-modal').modal('hide');
      },
      (err) => {
        console.error("Error adding customer", err);
      }
    );
  }
};
