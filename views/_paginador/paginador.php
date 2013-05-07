<?php if (isset($this->_paginacion)): ?>
    <div class='row-fluid'>
        <div class="span7">
            <ul>
                <?php if ($this->_paginacion['primero']) : ?>
                    <li><a href="<?php echo $link . $this->_paginacion['primero']; ?>" title="Primero">&Lt;</a></li>
                <?php else : ?>
                    <li class="disabled"><span>&Lt;</span></li>
                <?php endif; ?>

                <?php if ($this->_paginacion['anterior']) : ?>
                    <li><a href="<?php echo $link . $this->_paginacion['anterior']; ?>" title="Anterior">&LT;</a></li>
                <?php else : ?>
                    <li class="disabled"><span>&LT;</span></li>
                <?php endif; ?>

                <?php for ($i = 0; $i < count($this->_paginacion['rango']); $i++): ?>
                    <?php if ($this->_paginacion['actual'] == $this->_paginacion['rango'][$i]): ?>
                        <li class="active"><span><?php echo $this->_paginacion['rango'][$i]; ?></span></li>
                    <?php else: ?>
                        <li><a href="<?php echo $link . $this->_paginacion['rango'][$i]; ?>" title="<?php echo $this->_paginacion['rango'][$i]; ?>"><?php echo $this->_paginacion['rango'][$i]; ?></a></li>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($this->_paginacion['siguiente'] != '') : ?>
                    <li><a href="<?php echo $link . $this->_paginacion['siguiente']; ?>" title="Siguiente">&GT;</a></li>
                <?php else : ?>
                    <li class="disabled"><span>&GT;</span></li>
                <?php endif; ?>

                <?php if ($this->_paginacion['ultimo'] != '') : ?>
                    <li><a href="<?php echo $link . $this->_paginacion['ultimo']; ?>" title="&Uacute;ltimo">&Gt;</a></li>
                <?php else : ?>
                    <li class="disabled"><a href="#">&Gt;</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="span5">
            <form class="form-search" name="frm_goto">
                <div class="input-append">
                    <input type="text" class="span4 search-query goto">
                    <button type="submit" id='goto_btn' class="btn">Ir..</button>
                </div>
                <span class='total' data-total='<?php echo $this->_paginacion['total'] ?>'>Page <strong><?php echo $this->_paginacion['actual'] ?></strong> de <strong><?php echo $this->_paginacion['total'] ?></strong></span>
            </form>
        </div>
    </div>
<?php endif; ?>