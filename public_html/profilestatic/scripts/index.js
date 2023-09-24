jQuery(document).ready(function () {
  init();
  
      
});
const menus = [
  {name: 'Món chính',
   items: [
    {name: 'Cà phê', price: '13k' },
    {name: 'Cà phê sữa', price: '15k' },
    {name: 'Bạc xĩu', price: '15k' },
    {name: 'Ca cao sữa', price: '18k' },
    {name: 'Ca cao cà phê sữa', price: '20k' },
    {name: 'Trà chanh', price: '12k' },
    {name: 'Trà tắc', price: '12k' },

  ]},
  {name: 'Nước ép',
   items: [
    {name: 'Cam', price: '25k' },
    {name: 'Ổi', price: '20k' },
    {name: 'Sơ ri', price: '20k' },
    {name: 'Đưa hấu', price: '20k' },
    {name: 'Cà rốt', price: '20k' },
    {name: 'Táo', price: '25k' },
    {name: 'Táo + Dâu', price: '40k' },
    {name: 'Táo + Thơm', price: '25k' },
    {name: 'Táo + Cam', price: '25k' },
    {name: 'Táo + Sơ ri', price: '25k' },
    {name: 'Thơm', price: '20k' },
    {name: 'Thơm + Dâu', price: '35k' },
    {name: 'Thơm + Ổi + Sơri', price: '30k' },
    {name: 'Thơm + Cam', price: '25k' },
    {name: 'Thơm + Ổi', price: '25k' },
    {name: 'Thơm + Sơri', price: '25k' },
    {name: 'Thơm + Cà rốt', price: '25k' },
    {name: 'Cam + Cà rốt', price: '25k' },

  ]},
  {name: 'Sinh tố',
   items: [
    {name: 'Dâu', price: '25k' },
    {name: 'Bơ', price: '20k' },
    {name: 'Đu đủ', price: '20k' },
    {name: 'Saboche', price: '20k' },
    {name: 'Mãng cầu', price: '20k' },
    {name: 'Mãng cầu cafe', price: '20k' }
  ]},
]
function init(){

  let index = 0; 
  for (const menu of menus) {
            index++;
            const $template = $('#template-menu');
            let node = $template.prop('content');
            let clone = node.cloneNode(true);
            $(clone).find('[name="name"]').html(menu.name);
            for (const item of menu.items) {
              const $template1 = $('#template-item');
              let node1 = $template1.prop('content');
              let clone1 = node1.cloneNode(true);

              $(clone1).find('[name="name"]').html(item.name);
              $(clone1).find('[name="price"]').html(item.price);

              $(clone).find('[name="items"]').append(clone1)
            }  
            $('#menu-content').append(clone);
            if(index != menus.length){
              $('#menu-content').append('<div class="divider-margin"></div>');
            }
  }

  
}